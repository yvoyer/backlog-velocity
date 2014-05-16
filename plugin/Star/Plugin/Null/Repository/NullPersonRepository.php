<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Repository;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class NullPersonRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Repository
 */
class NullPersonRepository implements MemberRepository
{
    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::findAll() not implemented yet.');
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return object
     */
    public function find($id)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::find() not implemented yet.');
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
        throw new \RuntimeException('Method ' . __CLASS__ . '::findOneBy() not implemented yet.');
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add(Entity $object)
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::add() not implemented yet.');
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::save() not implemented yet.');
    }

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Person|null
     */
    public function findOneByName($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
 