<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamRepository
{
    /**
     * @return Team[]
     */
    public function allTeams(): array;

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team
     * @throws EntityNotFoundException
     */
    public function findOneByName(string $name): Team;

    /**
     * @param TeamId $teamId
     *
     * @return Team
     * @throws EntityNotFoundException
     */
    public function getTeamWithIdentity(TeamId $teamId): Team;

    public function teamWithIdentityExists(TeamId $teamId): bool;

    public function teamWithNameExists(TeamName $name): bool;

    public function saveTeam(Team $team): void;
}
