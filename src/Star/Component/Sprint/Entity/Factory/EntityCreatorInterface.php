<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
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
     * @todo Remove Entity reference from interface
     * @return Entity
     */
    public function createObject($type);

    /**
     * Create a sprint object.
     *
     * @return Sprint
     */
    public function createSprint();

    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
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
     * @param string $name The name of the sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter($name);

    /**
     * Create a TeamMember.
     *
     * @return TeamMember
     */
    public function createTeamMember();
}
