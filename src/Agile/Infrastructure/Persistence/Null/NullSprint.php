<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintCommitment;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullSprint implements Sprint
{
    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return null;
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return array();
    }

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity()
    {
        return 0;
    }

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays()
    {
        return ManDays::fromInt(0);
    }

    /**
     * @return SprintName
     */
    public function getName()
    {
        return new SprintName('Null sprint');
    }

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return false;
    }

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return false;
    }

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted()
    {
        return false;
    }
    /**
     * @return TeamId
     */
    public function teamId() :TeamId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return ProjectId
     */
    public function projectId() :ProjectId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectId $projectId
     *
     * @return bool
     */
    public function matchProject(ProjectId $projectId)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start(int $estimatedVelocity, \DateTimeInterface $startedAt)
    {
        // Do nothing
    }

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     * @param \DateTimeInterface $endedAt
     */
    public function close(int $actualVelocity, \DateTimeInterface $endedAt)
    {
        // Do nothing
    }

    /**
     * Returns the real focus factor.
     *
     * @return integer
     */
    public function getFocusFactor()
    {
        return 0;
    }

    /**
     *
     * @return integer
     */
    public function getEstimatedVelocity()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getEstimatedVelocity() not implemented yet.');
    }

    /**
     * @param MemberId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(MemberId $member, ManDays $availableManDays)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}