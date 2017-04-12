<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Exception\EntityNotFoundException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamCollection implements TeamRepository
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
    public function findOneByName($name)
    {
        foreach ($this->teams as $team) {
            if ($team->getName()->toString() === $name) {
                return $team;
            }
        }

        throw EntityNotFoundException::objectWithAttribute(Team::class, 'name', $name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function teamWithNameExists($name)
    {
        return $this->teams->exists(function ($key, Team $team) use ($name) {
            return $team->getName()->toString() === $name;
        });
    }

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team)
    {
        $this->teams[] = $team;
    }

    /**
     * @return Team[]
     */
    public function allTeams()
    {
        return $this->teams->getValues();
    }
}
