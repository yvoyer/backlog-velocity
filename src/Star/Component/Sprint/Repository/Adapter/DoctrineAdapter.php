<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Adapter;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\Mapping;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Class DoctrineAdapter
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Adapter
 */
class DoctrineAdapter implements RepositoryManager
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
        return $this->objectManager->getRepository($this->mapping->getTeamMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return $this->objectManager->getRepository($this->mapping->getSprintMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprinterRepository
     */
    public function getSprinterRepository()
    {
        return $this->objectManager->getRepository($this->mapping->getSprinterMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintMemberRepository
     */
    public function getSprintMemberRepository()
    {
        return $this->objectManager->getRepository($this->mapping->getSprintMemberMapping());
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamMemberRepository
     */
    public function getTeamMemberRepository()
    {
        return $this->objectManager->getRepository($this->mapping->getTeamMemberMapping());
    }
}
