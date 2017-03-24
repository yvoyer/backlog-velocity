<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;

/**
 * Class Sprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface Sprint
{
    /**
     * When the sprint is not started yet (Default)
     */
    const STATUS_INACTIVE = 0;

    /**
     * When the sprint is started
     */
    const STATUS_STARTED = 1;

    /**
     * Sprint is closed
     */
    const STATUS_CLOSED = 2;

    /**
     * @return SprintId
     */
    public function getId();

    /**
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity();

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getManDays();

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns whether the sprint is closed
     *
     * @return boolean
     */
    public function isClosed();

    /**
     * Returns whether the sprint is started
     *
     * @return boolean
     */
    public function isStarted();

    /**
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     */
    public function start($estimatedVelocity, \DateTimeInterface $startedAt);

    /**
     * Close a sprint.
     *
     * @param integer $actualVelocity
     */
    public function close($actualVelocity);

    /**
     * Returns the real focus factor.
     *
     * @return integer
     */
    public function getFocusFactor();

    /**
     *
     * @return integer
     */
    public function getEstimatedVelocity();

    /**
     * @param PersonId $member
     * @param ManDays  $availableManDays
     *
     * @return SprintCommitment
     */
    public function commit(PersonId $member, ManDays $availableManDays);

    /**
     * @return SprintCommitment[]
     */
    public function getCommitments();

    /**
     * @param ProjectId $projectId
     *
     * @return bool
     */
    public function matchProject(ProjectId $projectId);
}
