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

    /**
     * Add the $object linked to the $id.
     *
     * @param IdentifierInterface $id
     * @param mixed               $object
     */
    public function add(IdentifierInterface $id, $object)
    {
        $this->objects[$id->getKey()] = $object;
    }
}
