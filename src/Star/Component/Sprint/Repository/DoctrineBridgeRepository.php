<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class DoctrineBridgeRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 */
class DoctrineBridgeRepository implements Repository
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    public function __construct($entityClass, ObjectManager $objectManager)
    {
        $this->entityClass   = $entityClass;
        $this->objectManager = $objectManager;
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
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
        return $this->getRepository()->find($id);
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add(Entity $object)
    {
        $this->objectManager->persist($object);
    }

    /**
     * Returns the embeded repository.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository()
    {
        return $this->objectManager->getRepository($this->entityClass);
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        $this->objectManager->flush();
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
        return $this->getRepository()->findOneBy($criteria);
    }
}
