<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class InMemoryRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 *
 * @deprecated Never used, could be removed
 */
class InMemoryRepository implements Repository
{
    /**
     * @var Entity[]
     */
    private $objects = array();

    /**
     * Returns all the object from one repository.
     *
     * @return Entity[]
     */
    public function findAll()
    {
        return $this->objects;
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return Entity|null
     */
    public function find($id)
    {
        $value = null;
        if (array_key_exists($id, $this->objects)) {
            $value = $this->objects[$id];
        }

        return $value;
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add(Entity $object)
    {
        $id = $object->getId();

        $this->objects[$id] = $object;
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        return true;
    }

    /**
     * Returns the object matching the $criteria.
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }
}
