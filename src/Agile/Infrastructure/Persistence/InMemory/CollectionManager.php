<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\InMemory;

use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;
use Star\BacklogVelocity\Agile\RepositoryManager;

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
