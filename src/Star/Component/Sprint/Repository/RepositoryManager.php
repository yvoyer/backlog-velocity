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
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;

/**
 * Class RepositoryManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
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
     * @return TeamMemberRepository
     */
    public function getTeamMemberRepository();

    /**
     * @return PersonRepository
     */
    public function getPersonRepository();
}
