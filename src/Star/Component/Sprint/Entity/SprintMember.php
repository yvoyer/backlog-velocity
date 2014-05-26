<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class SprintMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 * todo remove Sprinter extends
 */
interface SprintMember extends Sprinter
{
    /**
     * Returns the available man days.
     *
     * @return integer
     */
    public function getAvailableManDays();

    /**
     * Returns the actual velocity.
     *
     * @return integer
     */
    public function getActualVelocity();

    /**
     * Returns the sprint.
     *
     * @return Sprint
     */
    public function getSprint();

    /**
     * Returns the team member.
     *
     * @return TeamMember
     */
    public function getTeamMember();

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();
}
