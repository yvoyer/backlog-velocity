<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;

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
     * @return Team
     * @throws EntityNotFoundException
     */
    public function findOneByName($name)
    {
        $team = $this->findOneBy(array('name' => $name));
        if (! $team) {
            throw EntityNotFoundException::objectWithAttribute(Team::class, 'name', $name);
        }

        return $team;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function teamWithNameExists($name)
    {
        return (bool) $this->findOneBy(array('name' => $name));
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
