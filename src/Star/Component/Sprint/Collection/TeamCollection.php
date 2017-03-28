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
     * @return Team|null
     */
    public function findOneByName($name)
    {
        foreach ($this->teams as $team) {
            if ($team->getName() === $name) {
                return $team;
            }
        }

        return null;
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
        return $this->teams->getValues();
    }
}
