<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

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
    public function findOneByName(string $name): Team
    {
        throw new \RuntimeException('Method findOneByName() not implemented yet.');
    }

    /**
     * @param TeamId $teamId
     *
     * @return Team
     */
    public function getTeamWithIdentity(TeamId $teamId): Team
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return Team[]
     */
    public function allTeams(): array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function teamWithNameExists(TeamName $name): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function teamWithIdentityExists(TeamId $teamId): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
