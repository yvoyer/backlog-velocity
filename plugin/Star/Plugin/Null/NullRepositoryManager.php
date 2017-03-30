<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\Component\Sprint\Collection\ProjectCollection;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Plugin\Null\Repository\NullPersonRepository;
use Star\Plugin\Null\Repository\NullSprintRepository;
use Star\Plugin\Null\Repository\NullTeamRepository;

/**
 * Class NullRepositoryManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null
 * @deprecated todo change null plugin to InMemoryPlugin that uses Collections
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
