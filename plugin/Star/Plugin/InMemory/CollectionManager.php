<?php

namespace Star\Plugin\InMemory;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\ProjectCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\RepositoryManager;

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
