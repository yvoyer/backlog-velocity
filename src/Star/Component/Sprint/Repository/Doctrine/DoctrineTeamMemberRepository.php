<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;

/**
 * Class DoctrineTeamMemberRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Doctrine
 */
class DoctrineTeamMemberRepository extends DoctrineRepository implements TeamMemberRepository
{
    /**
     * Return the Repository
     *
     * @return TeamMemberRepository
     */
    protected function getRepository()
    {
        return $this->getAdapter()->getTeamMemberRepository();
    }
}
