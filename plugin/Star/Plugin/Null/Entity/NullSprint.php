<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Calculator\FocusCalculator;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullSprint
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Entity
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
     * @return int
     */
    public function getManDays()
    {
        return 0;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        // do nothing
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
     * Start a sprint.
     *
     * @param int $estimatedVelocity
     */
    public function start($estimatedVelocity)
    {
        // Do nothing
    }

    /**
     * @param int             $actualVelocity
     * @param FocusCalculator $calculator
     */
    public function close($actualVelocity, FocusCalculator $calculator)
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
     * @return integer
     */
    public function getAvailableManDays()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getAvailableManDays() not implemented yet.');
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
     * @return Team
     */
    public function getTeam()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::getTeam() not implemented yet.');
    }

    /**
     * @param TeamMember $member
     * @param int $availableManDays
     *
     * @return SprintMember
     */
    public function commit(TeamMember $member, $availableManDays)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
