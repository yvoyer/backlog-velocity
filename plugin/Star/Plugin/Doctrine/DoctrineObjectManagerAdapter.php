<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Class DoctrineObjectManagerAdapter
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine
 */
class DoctrineObjectManagerAdapter implements RepositoryManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamRepository
     */
    public function getTeamRepository()
    {
        return $this->objectManager->getRepository(TeamModel::class);
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return $this->objectManager->getRepository(SprintModel::class);
    }

    /**
     * @return PersonRepository
     */
    public function getPersonRepository()
    {
        return $this->objectManager->getRepository(PersonModel::class);
    }

    /**
     * @return ProjectRepository
     */
    public function getProjectRepository()
    {
        return $this->objectManager->getRepository(ProjectAggregate::class);
    }
}
