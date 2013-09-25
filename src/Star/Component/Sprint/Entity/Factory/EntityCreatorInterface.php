<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\EntityInterface;
use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class EntityCreatorInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
interface EntityCreatorInterface
{
    const TYPE_SPRINT = 'sprint';
    const TYPE_TEAM = 'team';
    const TYPE_SPRINT_MEMBER = 'sprint_member';
    const TYPE_SPRINTER = 'sprinter';
    const TYPE_TEAM_MEMBER = 'team_member';

    /**
     * Create an object of $type.
     *
     * @param string $type
     *
     * @return EntityInterface
     */
    public function createObject($type);

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
     * @param string $name The name of the team.
     *
     * @return TeamInterface
     */
    public function createTeam($name);

    /**
     * Create a SprintMember.
     *
     * @return SprintMember
     */
    public function createSprintMember();

    /**
     * Create a Sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter();

    /**
     * Create a TeamMember.
     *
     * @return TeamMember
     */
    public function createTeamMember();
}
