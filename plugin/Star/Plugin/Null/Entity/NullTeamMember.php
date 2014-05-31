<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullTeamMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Entity
 */
class NullTeamMember implements TeamMember
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
     * Returns the member.
     *
     * @return Person
     */
    public function getPerson()
    {
        return new NullPerson();
    }

    /**
     * Returns the team.
     *
     * @return Team
     */
    public function getTeam()
    {
        return new NullTeam();
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
     * Returns the available man days for the team member.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $manDays
     */
    public function setAvailableManDays($manDays)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
