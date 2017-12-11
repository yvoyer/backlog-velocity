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
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;

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
    public function findOneByName(string $name) :Team
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
    public function allTeams() :array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param TeamName $name
     *
     * @return bool
     */
    public function teamWithNameExists(TeamName $name) :bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param TeamId $teamId
     *
     * @return bool
     */
    public function teamWithIdentityExists(TeamId $teamId): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
