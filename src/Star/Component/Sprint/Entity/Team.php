<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface Team extends Entity
{
    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName();

    /**
     * Add the $member to the team.
     *
     * @param Sprinter $member
     *
     * @return TeamMember
     */
    public function addMember(Sprinter $member);
}
