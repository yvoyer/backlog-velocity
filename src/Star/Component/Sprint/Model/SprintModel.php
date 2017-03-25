<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Sprint\Calculator\FocusCalculator;
use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Exception\Sprint\SprintNotStartedException;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Exception\Sprint\AlreadyCommittedSprintMemberException;
use Star\Component\Sprint\Exception\Sprint\AlreadyStartedSprintException;
use Star\Component\Sprint\Exception\Sprint\NoSprintMemberException;
use Star\Component\Sprint\Exception\Sprint\SprintNotClosedException;

/**
 * Class SprintModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprintModel /* todo extends AggregateRoot */implements Sprint
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var SprintCommitment[]
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
    private $status = self::STATUS_INACTIVE;

    /**
     * @var \DateTimeInterface|null
     */
    private $startedAt;

    /**
     * @var \DateTimeInterface|null
     */
    private $endedAt;

    /**
     * @param SprintId $id
     * @param string $name
     * @param ProjectId $project
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(SprintId $id, $name, ProjectId $project, \DateTimeInterface $createdAt)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->id = $id->toString();
        $this->name = $name;
        $this->projectId = $project;
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
     * Returns the Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
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
     * @throws \Star\Component\Sprint\Exception\Sprint\SprintNotClosedException
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
        return $this->commitments;
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     * @deprecated todo Rename to end() and hasEnded
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
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
        // todo throw exception if never started

        return new \DateTimeImmutable($this->endedAt->format('Y-m-d H:i:s'));
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return $this->status === self::STATUS_STARTED;
    }

    // todo add Drop() and archive state

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     * @throws \Star\Component\Sprint\Exception\Sprint\NoSprintMemberException
     * @throws \Star\Component\Sprint\Exception\Sprint\AlreadyStartedSprintException
     */
    public function start($estimatedVelocity, \DateTimeInterface $startedAt)
    {
        if ($this->isStarted()) {
            throw new AlreadyStartedSprintException('The sprint is already started.');
        }

        if (0 === $this->commitments->count()) {
            throw new NoSprintMemberException('Cannot start a sprint with no sprint members.');
        }

        $this->status = self::STATUS_STARTED;
        $this->estimatedVelocity = $estimatedVelocity;
        $this->startedAt = $startedAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function startedAt()
    {
        if (! $this->isStarted()) {
            throw SprintNotStartedException::cannotPerformOperationWhenNotStarted('ask for start date');
        }

        return new \DateTimeImmutable($this->startedAt->format('Y-m-d H:i:s'));
    }

    /**
     * @param PersonId $member
     * @param ManDays $availableManDays
     *
     * @return SprintCommitment
     * @throws AlreadyCommittedSprintMemberException
     */
    public function commit(PersonId $member, ManDays $availableManDays)
    {
        if ($this->memberIsCommited($member)) {
            // todo use $personId->formatted() // Person name
            throw new AlreadyCommittedSprintMemberException("The sprint member '{$member->toString()}' is already committed.");
        }

        $commitment = new SprintCommitment($availableManDays, $this, $member);
        $this->commitments[] = $commitment;

        return $commitment;
    }

    /**
     * @param PersonId $id
     *
     * @return bool
     */
    private function memberIsCommited(PersonId $id)
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
    public function close($actualVelocity, \DateTimeInterface $endedAt)
    {
        if ($this->isClosed()) {
            throw new InvalidArgumentException('Cannot close a sprint that is already closed.');
        }

        if (false === $this->isStarted() && false === $this->isClosed()) {
            throw new InvalidArgumentException('Cannot close a sprint that is not started.');
        }

        $this->status = self::STATUS_CLOSED;
        $this->actualVelocity = $actualVelocity;
        $this->endedAt = $endedAt;
    }
}
