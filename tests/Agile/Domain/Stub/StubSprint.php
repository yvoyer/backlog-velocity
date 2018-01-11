<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Stub;

use Star\BacklogVelocity\Agile\Domain\Model\FocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintCommitment;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class StubSprint implements Sprint
{
    /**
     * @var SprintId
     */
    private $id;

    /**
     * @var int
     */
    private $focusFactor;

    /**
     * @var int
     */
    private $state;

    const CREATED = 1;
    const STARTED = 2;
    const CLOSED = 3;

    /**
     * @var SprintCommitment[]
     */
    private $commitments = [];

    /**
     * @var int
     */
    private $manDays = 0;

    /**
     * @var ProjectId|null
     */
    private $project;

    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @param SprintId $id
     * @param int $focusFactor
     */
    private function __construct(SprintId $id, $focusFactor)
    {
        $this->id = $id;
        $this->focusFactor = $focusFactor;
        $this->state = self::CREATED;
    }

    public function getFocusFactor(): FocusFactor
    {
        return FocusFactor::fromInt((int) $this->focusFactor);
    }

    /**
     * @return ProjectId
     */
    public function projectId(): ProjectId
    {
        return $this->project;
    }
    /**
     * @return TeamId
     */
    public function teamId(): TeamId
    {
        return $this->teamId;
    }

    /**
     * @return SprintId
     */
    public function getId(): SprintId
    {
        if (! $this->id instanceof SprintId) {
            throw new \RuntimeException('The sprint id is not configured yet.');
        }

        return $this->id;
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return Velocity
     */
    public function getActualVelocity(): Velocity
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays(): ManDays
    {
        return ManDays::fromInt($this->manDays);
    }

    /**
     * @return SprintName
     */
    public function getName(): SprintName
    {
        return new SprintName($this->getId()->toString());
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed(): bool
    {
        return $this->state === self::CLOSED;
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted(): bool
    {
        return $this->state === self::STARTED;
    }

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start(int $estimatedVelocity, \DateTimeInterface $startedAt)
    {
        $this->state = self::STARTED;
    }

    /**
     * @return Velocity
     */
    public function getEstimatedVelocity(): Velocity
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param MemberId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(MemberId $member, ManDays $availableManDays): SprintCommitment
    {
    }

    /**
     * Close a sprint.
     *
     * @param Velocity $actualVelocity
     * @param FocusFactor $actualFocus
     * @param \DateTimeInterface $closedAt
     */
    public function close(Velocity $actualVelocity, FocusFactor $actualFocus, \DateTimeInterface $closedAt)
    {
        $this->state = self::CLOSED;
    }

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments(): array
    {
        return $this->commitments;
    }

    /**
     * @param SprintId $id
     *
     * @return StubSprint
     */
    public static function withId(SprintId $id): StubSprint
    {
        $sprint = new self($id, 0);
        $sprint->project = ProjectId::fromString(uniqid('pID-'));

        return $sprint;
    }

    /**
     * @param int $factor
     * @param TeamId $teamId
     *
     * @return StubSprint
     */
    public static function withFocus($factor, TeamId $teamId): StubSprint
    {
        $sprint = new self(SprintId::uuid(), $factor);
        $sprint->state = self::CLOSED;
        $sprint->teamId = $teamId;

        return $sprint;
    }
}
