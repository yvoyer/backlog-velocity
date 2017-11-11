<?php

namespace Star\Plugin\InMemory;

use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Repository\RepositoryManager;

final class CollectionManager implements RepositoryManager
{
    /**
     * @return ProjectRepository
     */
    public function getProjectRepository()
    {
        return new ProjectCollection();
    }

    /**
     * @return TeamRepository
     */
    public function getTeamRepository()
    {
        return new TeamCollection();
    }

    /**
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return new SprintCollection();
    }

    /**
     * @return PersonRepository
     */
    public function getPersonRepository()
    {
        return new PersonCollection();
    }
}
