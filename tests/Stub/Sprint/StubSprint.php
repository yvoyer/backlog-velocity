<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Stub\Sprint;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\SprintCommitment;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class StubSprint implements Sprint
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
     * @var int|null
     */
    private $manDays;

    /**
     * @var ProjectId|null
     */
    private $project;

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

    public function getFocusFactor()
    {
        return $this->focusFactor;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->project;
    }

    /**
     * @param ProjectId $projectId
     *
     * @return bool
     */
    public function matchProject(ProjectId $projectId)
    {
        return $projectId->matchIdentity($this->project);
    }

    /**
     * @return SprintId
     */
    public function getId()
    {
        if (! $this->id instanceof SprintId) {
            throw new \RuntimeException('The sprint id is not configured yet.');
        }

        return $this->id;
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays()
    {
        return ManDays::fromInt($this->manDays);
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getId()->toString();
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->state === self::CLOSED;
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return $this->state === self::STARTED;
    }

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start($estimatedVelocity, \DateTimeInterface $startedAt)
    {
        $this->state = self::STARTED;
    }

    /**
     *
     * @return integer
     */
    public function getEstimatedVelocity()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param PersonId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(PersonId $member, ManDays $availableManDays)
    {
    }

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     * @param \DateTimeInterface $endedAt
     */
    public function close($actualVelocity, \DateTimeInterface $endedAt)
    {
        $this->state = self::CLOSED;
    }

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments()
    {
        return $this->commitments;
    }

    /**
     * @return StubSprint
     */
    public function active()
    {
        return $this;
    }

    /**
     * @param int $manDays
     * @param string $personId
     *
     * @return StubSprint
     */
    public function withCommitment($manDays, $personId)
    {
        $this->commitments[] = new SprintCommitment(
            ManDays::fromInt($manDays),
            $this,
            PersonId::fromString($personId)
        );

        return $this;
    }

    /**
     * @param ProjectId $projectId
     * @param ManDays $days
     */
    public function withManDays(ProjectId $projectId, ManDays $days)
    {
        $this->project = $projectId;
        $this->manDays = $days->toInt();
    }

    /**
     * @param SprintId $id
     *
     * @return StubSprint
     */
    public static function withId(SprintId $id)
    {
        return new self($id, 0);
    }

    /**
     * @param int $factor
     * @param ProjectId $projectId
     *
     * @return StubSprint
     */
    public static function withFocus($factor, ProjectId $projectId)
    {
        $sprint = new self(SprintId::uuid(), $factor);
        $sprint->state = self::CLOSED;
        $sprint->project = $projectId;

        return $sprint;
    }

    /**
     * @param SprintId $id
     *
     * @return StubSprint
     */
    public static function closed(SprintId $id) {
        $sprint = self::withId($id);
        $sprint->state = self::CLOSED;

        return $sprint;
    }
}
