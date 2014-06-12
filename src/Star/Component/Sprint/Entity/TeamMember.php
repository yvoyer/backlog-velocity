<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Class TeamMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface TeamMember
{
    /**
     * @return Person
     */
    public function getPerson();

    /**
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
     * @deprecated todo remove
     */
    public function setAvailableManDays($manDays);//todo remove

    /**
     * @return string
     */
    public function getName();
}
