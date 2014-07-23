<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Team;

/**
 * Class TeamCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class TeamCollection extends TypedCollection
{
    public function __construct(array $teams = array())
    {
        parent::__construct('Star\Component\Sprint\Entity\Team', $teams);
    }

    protected function create(array $elements = array())
    {
        return new self($elements);
    }

    /**
     * @param Team $team
     * @deprecated todo use addTeam instead
     */
    public function add($team)
    {
        $this->addTeam($team);
    }

    /**
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this[] = $team;
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
        foreach ($this as $team) {
            if ($team->getName() === $name) {
                return $team;
            }
        }

        return null;
    }
}
 