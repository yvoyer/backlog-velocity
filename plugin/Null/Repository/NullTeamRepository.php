<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Repository;

use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullTeamRepository implements TeamRepository
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
        throw new \RuntimeException('Method findOneByName() not implemented yet.');
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return Team[]
     */
    public function allTeams()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function teamWithNameExists($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}