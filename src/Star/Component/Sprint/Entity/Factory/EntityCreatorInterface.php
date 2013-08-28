<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\TeamInterface;

/**
 * Class EntityCreatorInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
interface EntityCreatorInterface
{
    /**
     * Create a member object.
     *
     * @return MemberInterface
     */
    public function createMember();

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
     */
    public function createSprint();

    /**
     * Create a team object.
     *
     * @return TeamInterface
     */
    public function createTeam();
}
