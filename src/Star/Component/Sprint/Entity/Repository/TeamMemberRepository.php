<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class TeamMemberRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
interface TeamMemberRepository extends Repository
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * @param string $personName
     * @param string $sprintName
     *
     * @return TeamMember
     */
    public function findMemberOfSprint($personName, $sprintName);
}
