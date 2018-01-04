<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile;

use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

/**
 * Class RepositoryManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface RepositoryManager
{
    /**
     * Returns the Team repository.
     *
     * @return TeamRepository
     */
    public function getTeamRepository();

    /**
     * Returns the Team repository.
     *
     * @return SprintRepository
     */
    public function getSprintRepository();

    /**
     * @return PersonRepository
     */
    public function getPersonRepository();

    /**
     * @return ProjectRepository
     */
    public function getProjectRepository();
}
