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

    /**
     * Add the $object referenced with $id.
     *
     * @param IdentifierInterface $id
     * @param mixed               $object
     */
    public function add(IdentifierInterface $id, $object)
    {
        $this->sprints[$id->getKey()] = $object;
    }
}
