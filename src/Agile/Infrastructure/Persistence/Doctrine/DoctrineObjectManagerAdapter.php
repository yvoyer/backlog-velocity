<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrineObjectManagerAdapter
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
