<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface RepositoryManager
{
    /**
     * @return ProjectRepository
     */
    public function getProjectRepository();

    /**
     * @return TeamRepository
     */
    public function getTeamRepository();

    /**
     * @return SprintRepository
     */
    public function getSprintRepository();

    /**
     * @return PersonRepository
     */
    public function getPersonRepository();
}
