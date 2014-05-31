<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullSprintMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Entity
 */
class NullSprintMember implements SprintMember
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
     * Returns the available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return 0;
    }

    /**
     * Returns the sprint.
     *
     * @return Sprint
     */
    public function getSprint()
    {
        return new NullSprint();
    }

    /**
     * Returns the team member.
     *
     * @return TeamMember
     */
    public function getTeamMember()
    {
        return new NullTeamMember();
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
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
