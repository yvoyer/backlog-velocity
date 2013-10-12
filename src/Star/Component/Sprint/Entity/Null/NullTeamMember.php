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
 * Class NullTeamMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Null
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
     * @return Sprinter
     */
    public function getMember()
    {
        return new NullSprinter();
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
}
