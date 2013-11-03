<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Null;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullTeam
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Null
 */
class NullTeam implements Team
{
    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param Sprinter $sprinter
     * @param integer  $availableManDays
     *
     * @return TeamMember
     */
    public function addMember(Sprinter $sprinter, $availableManDays)
    {
        return new NullTeamMember();
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        // Do nothing
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        // Do nothing
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getMembers()
    {
        return array();
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
     * Returns the team available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return 0;
    }
}
