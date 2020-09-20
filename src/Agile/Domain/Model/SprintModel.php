<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\BacklogVelocity\Agile\Domain\Model\Event\NotImplementEventCallback;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasClosed;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasStarted;
use Star\BacklogVelocity\Agile\Domain\Model\Event\TeamMemberCommittedToSprint;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\AlreadyCommittedSprintMemberException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\NoSprintMemberException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintLogicException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintNotClosedException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintNotStartedException;
use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMachine;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintModel extends AggregateRoot implements Sprint
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $team;

    /**
     * @var SprintCommitment[]|ArrayCollection
     */
    private $commitments;

    /**
     * @var integer
     */
    private $actualVelocity = 0;

    /**
     * @var int
     */
    private $plannedVelocity = 0;

    /**
     * @var int
     */
    private $currentFocus;

    /**
     * @var string
     */
    private $status = SprintStatus::PENDING;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $startedAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $endedAt;

    protected function __construct()
    {
    	parent::__construct();
        $this->commitments = new ArrayCollection();
    }

    public function getId(): SprintId
    {
        return SprintId::fromString($this->id);
    }

    protected function aggregateId(): string
    {
        return $this->getId()->toString();
    }

    public function getName(): SprintName
    {
        return new SprintName($this->name);
    }

    public function teamId(): TeamId
    {
        return TeamId::fromString($this->team);
    }

    public function projectId(): ProjectId
    {
        return ProjectId::fromString($this->project);
    }

    public function getFocusFactor(): FocusFactor
    {
        if (false === $this->isClosed()) {
            throw new SprintNotClosedException('The sprint is not closed, the focus cannot be determined.');
        }

        return FocusFactor::fromInt($this->currentFocus);
    }

    public function getPlannedVelocity(): Velocity
    {
        return Velocity::fromInt($this->plannedVelocity);
    }

    public function getActualVelocity(): Velocity
    {
        return Velocity::fromInt($this->actualVelocity);
    }

    public function getManDays(): ManDays
    {
        $availableManDays = ManDays::fromInt(0);
        foreach ($this->commitments as $commitment) {
            $availableManDays = $availableManDays->addManDays($commitment->getAvailableManDays());
        }

        return $availableManDays;
    }

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments(): array
    {
        return $this->commitments->getValues();
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isClosed(): bool
    {
        return $this->state()->isInState(SprintStatus::CLOSED);
    }

    public function endedAt(): \DateTimeInterface
    {
        if (! $this->isClosed()) {
            throw SprintNotClosedException::cannotPerformOperationWhenNotEnded('ask for end date');
        }

        return new \DateTimeImmutable($this->endedAt->format('Y-m-d H:i:s'));
    }

    public function isStarted(): bool
    {
        return $this->state()->isInState(SprintStatus::STARTED);
    }

    public function start(int $plannedVelocity, \DateTimeInterface $startedAt): void
    {
        $this->apply(SprintWasStarted::version1($this->getId(), $plannedVelocity, $startedAt));
    }

    protected function whenSprintWasStarted(SprintWasStarted $event): void
    {
        $this->status = $this->state()->transit('start', $this);

        if (0 === $this->commitments->count()) {
            throw new NoSprintMemberException('Cannot start a sprint with no sprint members.');
        }

        $this->plannedVelocity = $event->plannedVelocity();
        $this->startedAt = $event->startedAt();
    }

    public function startedAt(): \DateTimeInterface
    {
        if (! $this->startedAt instanceof \DateTimeInterface) {
            throw SprintNotStartedException::cannotPerformOperationWhenNotStarted('ask for start date');
        }

        return new \DateTimeImmutable($this->startedAt->format('Y-m-d H:i:s'));
    }

    public function commit(MemberId $member, ManDays $availableManDays): SprintCommitment
    {
        // todo Add event
        if (! $this->canCommit()) {
            throw SprintLogicException::cannotCommitSprintInState($this->status);
        }

        if ($this->memberIsCommited($member)) {
            // todo use $personId->formatted() // Person name
            throw new AlreadyCommittedSprintMemberException("The sprint member '{$member->toString()}' is already committed.");
        }

        $commitment = new SprintCommitment($availableManDays, $this, $member);
        $this->commitments[] = $commitment;

        return $commitment;
    }

    private function memberIsCommited(MemberId $id): bool
    {
        return $this->commitments->exists(function($key, SprintCommitment $commitment) use ($id) {
            return $id->matchIdentity($commitment->member());
        });
    }

    public function close(Velocity $actualVelocity, \DateTimeInterface $closedAt): void
    {
        $this->apply(
            SprintWasClosed::version1(
                $this->getId(),
                $actualVelocity,
                $closedAt
            )
        );
    }

    protected function whenSprintWasClosed(SprintWasClosed $event): void
    {
        $this->status = $this->state()->transit('close', $this);
        if ($event->endedAt() < $this->startedAt) {
            throw new InvalidArgumentException('The sprint end date cannot be lower than the start date.');
        }

        $this->actualVelocity = $event->actualVelocity();
        $this->currentFocus = (int) (($this->getActualVelocity()->toInt() / $this->getPlannedVelocity()->toInt()) * 100);
        $this->endedAt = $event->endedAt();
    }

    /**
     * @param DomainEvent[] $events
     *
     * @return static
     */
    public static function fromStream(array $events): SprintModel
    {
        return static::reconstituteFromHistory(new \ArrayIterator($events));
    }

    public static function pendingSprint(
        SprintId $id,
        SprintName $name,
        ProjectId $projectId,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ): SprintModel {
        return self::fromStream(
            [
                SprintWasCreated::version1(
                    $id,
                    $name,
                    $projectId,
                    $teamId,
                    $createdAt
                )
            ]
        );
    }

    protected function whenSprintWasCreated(SprintWasCreated $event): void
    {
        $this->id = $event->sprintId()->toString();
        $this->name = $event->name()->toString();
        $this->project = $event->projectId()->toString();
        $this->team = $event->teamId()->toString();
        $this->createdAt = $event->addedAt();
    }

    /**
     * @param SprintId $id
     * @param SprintName $name
     * @param ProjectId $projectId
     * @param TeamId $teamId
     * @param Velocity $velocity
     * @param array $commitments Key value pair with 'memberId' and 'manDays'
     *
     * @return SprintModel
     * @throws AlreadyCommittedSprintMemberException
     * @throws NoSprintMemberException
     */
    public static function startedSprint(
        SprintId $id,
        SprintName $name,
        ProjectId $projectId,
        TeamId $teamId,
        Velocity $velocity,
        array $commitments
    ): SprintModel {
        $sprint = self::pendingSprint(
            $id,
            $name,
            $projectId,
            $teamId,
            new \DateTimeImmutable()
        );

        foreach ($commitments as $commitment) {
            $sprint->commit(MemberId::fromString($commitment['memberId']), ManDays::fromInt($commitment['manDays']));
        }
        $sprint->start($velocity->toInt(), new \DateTimeImmutable());

        return $sprint;
    }

    /**
     * @param SprintId $id
     * @param SprintName $name
     * @param ProjectId $projectId
     * @param TeamId $teamId
     * @param Velocity $velocity
     * @param Velocity $actualVelocity
     * @param array $commitments Key value pair with 'memberId' and 'manDays'
     *
     * @return SprintModel
     */
    public static function closedSprint(
        SprintId $id,
        SprintName $name,
        ProjectId $projectId,
        TeamId $teamId,
        Velocity $velocity,
        Velocity $actualVelocity,
        array $commitments
    ): SprintModel {
        $sprint = self::startedSprint(
            $id,
            $name,
            $projectId,
            $teamId,
            $velocity,
            $commitments
        );
        $sprint->close($actualVelocity, new \DateTimeImmutable());

        return $sprint;
    }

    protected function whenTeamMemberCommittedToSprint(TeamMemberCommittedToSprint $event): void
    {
        $this->commit($event->memberId(), $event->manDays());
    }

    /**
     * Transitions
     * +-----------+---------+---------+--------+----------+-----------+
     * | From / To | Pending | Started | Closed | Archived | Discarded |
     * +-----------+---------+---------+--------+----------+-----------+
     * | Pending   |   N/A   |  Allow  |  Deny  |   Deny   |   Allow   |
     * +-----------+---------+---------+--------+----------+-----------+
     * | Started   |   Deny  |  N/A    |  Allow |   Deny   |   Deny    |
     * +-----------+---------+---------+--------+----------+-----------+
     * | Closed    |   Deny  |  Deny   |  N/A   |   Allow  |   Deny    |
     * +-----------+---------+---------+--------+----------+-----------+
     * | Archived  |   Deny  |  Deny   |  Deny  |   N/A    |   Deny    |
     * +-----------+---------+---------+--------+----------+-----------+
     * | Discarded |   Deny  |  Deny   |  Deny  |   Deny   |    N/A    |
     * +-----------+---------+---------+--------+----------+-----------+
     *
     * Permissions
     * +-----------+---------+---------+--------+----------+-----------+
     * | From / To | Pending | Started | Closed | Archived | Discarded |
     * +-----------+---------+---------+--------+----------+-----------+
     * | can_commit|  Allow  |   Deny  |  Deny  |  TODO    |   TODO    |
     * +-----------+---------+---------+--------+----------+-----------+
     */
    private function state(): StateMachine
    {
        return StateBuilder::build()
            ->allowTransition('start', SprintStatus::PENDING, SprintStatus::STARTED)
            ->allowTransition('close', SprintStatus::STARTED, SprintStatus::CLOSED)
            ->addAttribute('can_commit', [SprintStatus::PENDING])
            ->create($this->status);
    }

    private function canCommit(): bool
    {
        return $this->state()->hasAttribute('can_commit');
    }

	protected function apply(AggregateChanged $event): void {
		if ($event instanceof SprintWasCreated) {
			$this->whenSprintWasCreated($event);
			return;
		}

    	if ($event instanceof SprintWasClosed) {
		    $this->whenSprintWasClosed($event);
		    return;
	    }

    	if ($event instanceof SprintWasStarted) {
		    $this->whenSprintWasStarted($event);
		    return;
	    }

    	if ($event instanceof TeamMemberCommittedToSprint) {
		    $this->whenTeamMemberCommittedToSprint($event);
		    return;
	    }

		throw new NotImplementEventCallback($event);
	}
}
