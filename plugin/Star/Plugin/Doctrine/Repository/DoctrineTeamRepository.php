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
 * Class DoctrineTeamRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
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
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
