<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Team;

use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Team;
use Star\Component\Sprint\Tests\Stub\Team\Team1;
use Star\Component\Sprint\Tests\Stub\Team\Team2;

/**
 * Class TeamRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Team
 */
class InMemoryTeamRepository implements Repository
{
    /**
     * @var Team[]
     */
    private $objects;

    public function __construct()
    {
        $team = new Team1();
        $this->objects[$team->getName()] = $team;
        $team = new Team2();
        $this->objects[$team->getName()] = $team;
    }

    /**
     * Returns all the object from one repository.
     *
     * @return Team[]
     */
    public function findAll()
    {
        return $this->objects;
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param IdentifierInterface $id
     *
     * @return Team
     */
    public function find(IdentifierInterface $id)
    {
        return $this->objects[$id->getKey()];
    }
}
