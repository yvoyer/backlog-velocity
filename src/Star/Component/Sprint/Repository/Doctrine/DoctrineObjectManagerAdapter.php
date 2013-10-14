<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Repository\Mapping;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Class DoctrineObjectManagerAdapter
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Doctrine
 *
 * @todo Create a RepositoryAdapter
 */
class DoctrineObjectManagerAdapter implements RepositoryManager, Repository
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @param ObjectManager $objectManager
     * @param Mapping       $mapping
     */
    public function __construct(ObjectManager $objectManager, Mapping $mapping)
    {
        $this->objectManager = $objectManager;
        $this->mapping       = $mapping;
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamRepository
     */
    public function getTeamRepository()
    {
        // @todo Use a RepositoryAdapter
        return $this->objectManager->getRepository($this->mapping->getTeamMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        // @todo Use a RepositoryAdapter
        return $this->objectManager->getRepository($this->mapping->getSprintMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprinterRepository
     */
    public function getSprinterRepository()
    {
        // @todo Use a RepositoryAdapter
        return $this->objectManager->getRepository($this->mapping->getSprinterMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintMemberRepository
     */
    public function getSprintMemberRepository()
    {
        // @todo Use a RepositoryAdapter
        return $this->objectManager->getRepository($this->mapping->getSprintMemberMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamMemberRepository
     */
    public function getTeamMemberRepository()
    {
        // @todo Use a RepositoryAdapter
        return $this->objectManager->getRepository($this->mapping->getTeamMemberMapping());
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        throw new \Exception("Feature can't be used from this scope");
        // TODO: Implement findAll() method.
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
        throw new \Exception("Feature can't be used from this scope");
        // TODO: Implement find() method.
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
        throw new \Exception("Feature can't be used from this scope");
        // TODO: Implement findOneBy() method.
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param Entity $object
     */
    public function add(Entity $object)
    {
        $this->objectManager->persist($object);
        // TODO: Implement add() method.
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        $this->objectManager->flush();
        // TODO: Implement save() method.
    }
}
