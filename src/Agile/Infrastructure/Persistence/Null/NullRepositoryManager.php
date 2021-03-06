<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\BacklogVelocity\Agile\RepositoryManager;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullRepositoryManager implements RepositoryManager
{
    /**
     * Returns the Team repository.
     *
     * @return TeamRepository
     */
    public function getTeamRepository()
    {
        return new NullTeamRepository();
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository()
    {
        return new NullSprintRepository();
    }

    /**
     * @return PersonRepository
     */
    public function getPersonRepository()
    {
        return new NullPersonRepository();
    }

    /**
     * @return ProjectRepository
     */
    public function getProjectRepository()
    {
        return new ProjectCollection();
    }
}
