<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Null;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Component\Sprint\Tests\Unit\Null\Repository\NullSprinterRepository;
use Star\Component\Sprint\Tests\Unit\Null\Repository\NullSprintMemberRepository;
use Star\Component\Sprint\Tests\Unit\Null\Repository\NullSprintRepository;
use Star\Component\Sprint\Tests\Unit\Null\Repository\NullTeamMemberRepository;
use Star\Component\Sprint\Tests\Unit\Null\Repository\NullTeamRepository;

/**
 * Class NullRepositoryManager
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Null
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
}
 