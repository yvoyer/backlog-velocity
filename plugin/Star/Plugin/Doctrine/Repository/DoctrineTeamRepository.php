<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrineTeamRepository extends EntityRepository implements TeamRepository
{
    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team|null
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @return Team[]
     */
    public function allTeams()
    {
        return $this->findAll();
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }
}
