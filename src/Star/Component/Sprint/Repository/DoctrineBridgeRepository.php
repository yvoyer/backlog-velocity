<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\EntityInterface;

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
        $this->entityClass = $entityClass;
        $this->objectManager = $objectManager;
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->objectManager->getRepository($this->entityClass)->findAll();
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
        return $this->objectManager->getRepository($this->entityClass)->find($id);
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param EntityInterface $object
     */
    public function add(EntityInterface $object)
    {
        $this->objectManager->persist($object);
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        $this->objectManager->flush();
    }
}
