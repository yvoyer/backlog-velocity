<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class Repository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 */
interface Repository
{
    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll();

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return object
     */
    public function find($id);

    /**
     * Returns the object matching the $criteria.
     *
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria);

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add($object);

    /**
     * Save the $object in the repository.
     */
    public function save();
}
