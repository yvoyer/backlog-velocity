<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class DoctrineTeamMemberRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineTeamMemberRepository extends DoctrineRepository implements TeamMemberRepository
{
    /**
     * @param string $personName
     * @param string $sprintName
     *
     * @return TeamMember
     */
    public function findMemberOfSprint($personName, $sprintName)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
