<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Sprint;

use Star\Component\Sprint\Entity\IdentifierInterface;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Sprint;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;

/**
 * Class SprintRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Sprint
 */
class InMemorySprintRepository implements Repository
{
    /**
     * @var Sprint[]
     */
    private $sprints = array();

    public function __construct()
    {
        $sprint = new Sprint1();
        $this->sprints[$sprint->getName()] = $sprint;
        $sprint = new Sprint2();
        $this->sprints[$sprint->getName()] = $sprint;
        $sprint = new Sprint3();
        $this->sprints[$sprint->getName()] = $sprint;
    }

    /**
     * Returns all the object from one repository.
     *
     * @return Sprint[]
     */
    public function findAll()
    {
        return $this->sprints;
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param IdentifierInterface $id
     *
     * @return Sprint
     */
    public function find(IdentifierInterface $id)
    {
        return $this->sprints[$id->getKey()];
    }
}
