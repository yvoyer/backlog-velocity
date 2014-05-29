<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class Sprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface Sprint extends Entity
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
     * Returns the actual velocity (Story point).
     *
     * @return int
     */
    public function getActualVelocity();

    /**
     * Returns the available man days.
     *
     * @return int
     */
    public function getManDays();

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name);

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
     */
    public function start($estimatedVelocity);

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
     * @return integer
     */
    public function getAvailableManDays();

    /**
     *
     * @return integer
     */
    public function getEstimatedVelocity();

    /**
     * @return Team
     */
    public function getTeam();
}
