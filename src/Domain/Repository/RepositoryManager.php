<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Repository;

use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 * todo Remove in favor of direct injection
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
