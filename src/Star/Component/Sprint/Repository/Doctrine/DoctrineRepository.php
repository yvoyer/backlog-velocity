<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class DoctrineRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Doctrine
 */
abstract class DoctrineRepository implements Repository
{
    /**
     * @var string
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param string        $repositoryClass
     * @param ObjectManager $objectManager
     */
    public function __construct($repositoryClass, ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->repository    = $this->objectManager->getRepository($repositoryClass);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->repository;
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
     * Save the $object in the repository.
     */
    public function save()
    {
        $this->objectManager->flush();
    }
}
