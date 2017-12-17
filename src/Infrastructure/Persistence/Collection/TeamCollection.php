<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamCollection implements TeamRepository, \Countable
{
    /**
     * @var TypedCollection|Team[]
     */
    private $teams;

    public function __construct(array $teams = array())
    {
        $this->teams = new TypedCollection(Team::class, $teams);
    }

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
        foreach ($this->teams as $team) {
            if ($team->getName()->toString() === $name) {
                return $team;
            }
        }

        throw EntityNotFoundException::objectWithAttribute(Team::class, 'name', $name);
    }

    /**
     * @param TeamId $teamId
     *
     * @return Team
     * @throws EntityNotFoundException
     */
    public function getTeamWithIdentity(TeamId $teamId): Team
    {
        $team = $this->teams->filter(
            function (Team $team) use ($teamId) {
            return $teamId->matchIdentity($team->getId());
        })->first();

        if (! $team) {
            throw EntityNotFoundException::objectWithIdentity($teamId);
        }

        return $team;
    }

    /**
     * @param TeamName $name
     *
     * @return bool
     */
    public function teamWithNameExists(TeamName $name) :bool
    {
        return $this->teams->exists(function ($key, Team $team) use ($name) {
            return $name->equals($team->getName());
        });
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team)
    {
        $this->teams[$team->getId()->toString()] = $team;
    }

    /**
     * @return Team[]
     */
    public function allTeams() :array
    {
        return $this->teams->getValues();
    }

    public function count() :int {
        return $this->teams->count();
    }

    /**
     * @param TeamId $teamId
     *
     * @return bool
     */
    public function teamWithIdentityExists(TeamId $teamId): bool
    {
        return $this->teams->exists(function ($key, Team $team) use ($teamId) {
            return $teamId->matchIdentity($team->getId());
        });
    }
}
