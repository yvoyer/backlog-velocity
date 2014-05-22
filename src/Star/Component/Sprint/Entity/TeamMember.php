<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class TeamMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface TeamMember extends Entity
{
    /**
     * Returns the member.
     *
     * @return Sprinter
     */
    public function getPerson();

    /**
     * Returns the team.
     *
     * @return Team
     */
    public function getTeam();

    /**
     * Returns the available man days for the team member.
     *
     * @return integer
     */
    public function getAvailableManDays();

    /**
     * @param int $manDays
     */
    public function setAvailableManDays($manDays);

    /**
     * @return string
     */
    public function getName();
}
