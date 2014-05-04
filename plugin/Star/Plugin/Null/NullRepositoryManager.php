<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Plugin\Null\Repository\NullPersonRepository;
use Star\Plugin\Null\Repository\NullSprinterRepository;
use Star\Plugin\Null\Repository\NullSprintMemberRepository;
use Star\Plugin\Null\Repository\NullSprintRepository;
use Star\Plugin\Null\Repository\NullTeamMemberRepository;
use Star\Plugin\Null\Repository\NullTeamRepository;

/**
 * Class NullRepositoryManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null
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
     * Returns the Team repository.
     *
     * @return SprinterRepository
     */
    public function getSprinterRepository()
    {
        return new NullSprinterRepository();
    }

    /**
     * Returns the Team repository.
     *
     * @return SprintMemberRepository
     */
    public function getSprintMemberRepository()
    {
        return new NullSprintMemberRepository();
    }

    /**
     * Returns the Team repository.
     *
     * @return TeamMemberRepository
     */
    public function getTeamMemberRepository()
    {
        return new NullTeamMemberRepository();
    }

    /**
     * @return MemberRepository
     */
    public function getPersonRepository()
    {
        return new NullPersonRepository();
    }
}
 