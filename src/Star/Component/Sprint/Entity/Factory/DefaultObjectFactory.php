<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamInterface;

/**
 * Class that handle the creation of all objects used by the project.
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class DefaultObjectFactory
{
    /**
     * Create a member object.
     *
     * @return MemberInterface
     */
    public function createMember()
    {
        $object = new Member();

        return $object;
    }

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
     */
    public function createSprint()
    {
        $object = new Sprint('');

        return $object;
    }

    /**
     * Create a team object.
     *
     * @return TeamInterface
     */
    public function createTeam()
    {
        $object = new Team('');

        return $object;
    }
}
