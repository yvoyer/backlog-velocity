<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Domain\Calculator\FocusCalculator;
use Star\Component\Sprint\Domain\Event\SprintWasClosed;
use Star\Component\Sprint\Domain\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Domain\Event\SprintWasStarted;
use Star\Component\Sprint\Domain\Event\TeamMemberCommitedToSprint;
use Star\Component\Sprint\Domain\Exception\Sprint\SprintLogicException;
use Star\Component\Sprint\Domain\Exception\Sprint\SprintNotStartedException;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Exception\InvalidArgumentException;
use Star\Component\Sprint\Domain\Exception\Sprint\AlreadyCommittedSprintMemberException;
use Star\Component\Sprint\Domain\Exception\Sprint\NoSprintMemberException;
use Star\Component\Sprint\Domain\Exception\Sprint\SprintNotClosedException;
use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateContext;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintModel extends AggregateRoot implements Sprint, StateContext
{
    /**
     * @var integer
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
    private $estimatedVelocity = 0;

    /**
     * @var int
     */
    private $status = SprintStatus::PENDING;

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
        $this->commitments = new ArrayCollection();
    }

    /**
     * Returns the unique id.
     *
     * @return SprintId
     */
    public function getId()
    {
        return SprintId::fromString($this->id);
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->getId()->toString();
    }

    /**
     * @return SprintName
     */
    public function getName()
    {
        return new SprintName($this->name);
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return ProjectId::fromString($this->project);
    }

    /**
     * @param ProjectId $projectId
     *
     * @return bool
     */
    public function matchProject(ProjectId $projectId)
    {
        return $this->projectId()->matchIdentity($projectId);
    }

    /**
     * Returns the real focus factor.
     *
     * @throws \Star\Component\Sprint\Domain\Exception\Sprint\SprintNotClosedException
     * @return integer
     */
    public function getFocusFactor()
    {
        if (false === $this->isClosed()) {
            throw new SprintNotClosedException('The sprint is not closed, the focus cannot be determined.');
        }

        $calculator = new FocusCalculator();
        return $calculator->calculate($this->getManDays()->toInt(), $this->getActualVelocity());
    }

    /**
     * @return integer
     */
    public function getEstimatedVelocity()
    {
        return $this->estimatedVelocity;
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        return $this->actualVelocity;
    }

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays()
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
    public function getCommitments()
    {
        return $this->commitments->getValues();
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     * @deprecated todo Rename to end() and hasEnded
     */
    public function isClosed()
    {
        return $this->state()->isInState(SprintStatus::CLOSED);
    }

    /**
     * @return \DateTimeInterface
     * @throws SprintNotClosedException
     */
    public function endedAt()
    {
        if (! $this->isClosed()) {
            throw SprintNotClosedException::cannotPerformOperationWhenNotEnded('ask for end date');
        }

        return new \DateTimeImmutable($this->endedAt->format('Y-m-d H:i:s'));
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return $this->state()->isInState(SprintStatus::STARTED);
    }

    // todo add Drop() and archive state

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     * @throws \Star\Component\Sprint\Domain\Exception\Sprint\NoSprintMemberException
     * @throws \Star\Component\State\InvalidStateTransitionException
     */
    public function start(int $estimatedVelocity, \DateTimeInterface $startedAt)
    {
        $this->apply(SprintWasStarted::version1($this->getId(), $estimatedVelocity, $startedAt));
    }

    protected function whenSprintWasStarted(SprintWasStarted $event)
    {
        $this->status = $this->state()->transitContext('start', $this);

        if (0 === $this->commitments->count()) {
            throw new NoSprintMemberException('Cannot start a sprint with no sprint members.');
        }

        $this->estimatedVelocity = $event->estimatedVelocity();
        $this->startedAt = $event->startedAt();
    }

    /**
     * @return \DateTimeInterface
     */
    public function startedAt()
    {
        if (! $this->startedAt instanceof \DateTimeInterface) {
            throw SprintNotStartedException::cannotPerformOperationWhenNotStarted('ask for start date');
        }

        return new \DateTimeImmutable($this->startedAt->format('Y-m-d H:i:s'));
    }

    /**
     * @param MemberId $member
     * @param ManDays $availableManDays
     *
     * @return SprintCommitment
     * @throws AlreadyCommittedSprintMemberException
     */
    public function commit(MemberId $member, ManDays $availableManDays)
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

    /**
     * @param MemberId $id
     *
     * @return bool
     */
    private function memberIsCommited(MemberId $id)
    {
        return $this->commitments->exists(function($key, SprintCommitment $commitment) use ($id) {
            return $id->matchIdentity($commitment->member());
        });
    }

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     * @param \DateTimeInterface $endedAt
     */
    public function close(int $actualVelocity, \DateTimeInterface $endedAt)
    {
        $this->apply(
            SprintWasClosed::version1($this->getId(), $actualVelocity, $endedAt)
        );
    }

    protected function whenSprintWasClosed(SprintWasClosed $event)
    {
        $this->status = $this->state()->transitContext('close', $this);
        if ($event->endedAt() < $this->startedAt) {
            throw new InvalidArgumentException('The sprint end date cannot be lower than the start date.');
        }

        $this->actualVelocity = $event->actualVelocity();
        $this->endedAt = $event->endedAt();
    }

    /**
     * @param DomainEvent[] $events
     *
     * @return static
     */
    public static function fromStream(array $events)
    {
        return static::reconstituteFromHistory(new \ArrayIterator($events));
    }

    /**
     * @param SprintId $id
     * @param SprintName $name
     * @param ProjectId $projectId
     * @param \DateTimeInterface $createdAt
     *
     * @return SprintModel
     */
    public static function pendingSprint(SprintId $id, SprintName $name, ProjectId $projectId, \DateTimeInterface $createdAt)
    {
        return self::fromStream(
            [
                SprintWasCreatedInProject::version1(
                    $id,
                    $projectId,
                    $name,
                    $createdAt
                )
            ]
        );
    }

    /**
     * @param SprintId $id
     * @param SprintName $name
     * @param ProjectId $projectId
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
        Velocity $velocity,
        array $commitments
    ) {
        $sprint = self::pendingSprint($id, $name, $projectId, new \DateTimeImmutable());
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
        Velocity $velocity,
        Velocity $actualVelocity,
        array $commitments
    ) {
        $sprint = self::startedSprint($id, $name, $projectId, $velocity, $commitments);
        $sprint->close($actualVelocity->toInt(), new \DateTimeImmutable());

        return $sprint;
    }

    protected function whenSprintWasCreatedInProject(SprintWasCreatedInProject $event)
    {
        $this->id = $event->sprintId()->toString();
        $this->name = $event->name()->toString();
        $this->project = $event->projectId()->toString();
    }

    protected function whenTeamMemberCommitedToSprint(TeamMemberCommitedToSprint $event)
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
    private function state()
    {
        return StateBuilder::build()
            ->allowTransition('start', SprintStatus::PENDING, SprintStatus::STARTED)
            ->allowTransition('close', SprintStatus::STARTED, SprintStatus::CLOSED)
            ->addAttribute('can_commit', [SprintStatus::PENDING])
            ->create($this->status);
    }

    /**
     *
     * @return bool
     */
    private function canCommit()
    {
        return $this->state()->hasAttribute('can_commit');
    }
}
