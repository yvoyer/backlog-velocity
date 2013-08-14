<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Prophecy\Exception\InvalidArgumentException;
use Star\Component\Sprint\Entity\EntityInterface;

/**
 * Class InMemoryRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 */
class InMemoryRepository implements Repository
{
    /**
     * @var EntityInterface[]
     */
    private $objects = array();

    /**
     * Returns all the object from one repository.
     *
     * @return EntityInterface[]
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
     * @return EntityInterface|null
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
     * @param EntityInterface $object
     * @throws \Prophecy\Exception\InvalidArgumentException
     */
    public function add(EntityInterface $object)
    {
        $id = $object->getId();
        if (empty($id)) {
            throw new InvalidArgumentException('The id is invalid');
        }

        $this->objects[$id] = $object;
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        // TODO: Implement save() method.
    }
}
