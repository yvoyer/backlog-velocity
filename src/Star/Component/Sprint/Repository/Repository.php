<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Entity\IdentifierInterface;

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
     * @param IdentifierInterface $id
     *
     * @return object
     */
    public function find(IdentifierInterface $id);

    /**
     * Add the $object linked to the $id.
     *
     * @param IdentifierInterface $id
     * @param object              $object
     */
    public function add(IdentifierInterface $id, $object);
}
