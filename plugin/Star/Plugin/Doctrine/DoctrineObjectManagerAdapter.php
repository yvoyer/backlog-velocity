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
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Plugin\Doctrine\Repository\DoctrinePersonRepository;
use Star\Plugin\Doctrine\Repository\DoctrineSprintRepository;
use Star\Plugin\Doctrine\Repository\DoctrineTeamRepository;

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
        return new DoctrineTeamRepository($this->objectManager->getRepository(TeamModel::CLASS_NAME), $this->objectManager);
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return new DoctrineSprintRepository($this->objectManager->getRepository(SprintModel::CLASS_NAME), $this->objectManager);
    }

    /**
     * @return PersonRepository
     */
    public function getPersonRepository()
    {
        return new DoctrinePersonRepository($this->objectManager->getRepository(PersonModel::CLASS_NAME), $this->objectManager);
    }

    /**
     * @return ProjectRepository
     */
    public function getProjectRepository()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
