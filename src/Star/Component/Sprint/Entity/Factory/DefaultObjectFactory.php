<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Null\NullSprinter;
use Star\Component\Sprint\Entity\Null\NullTeam;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;

/**
 * Class that handle the creation of all objects used by the project.
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class DefaultObjectFactory implements EntityCreator
{
    /**
     * Create a sprint object.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name)
    {
        // @todo inject name
        $object = new SprintData('', new TeamData(''));

        return $object;
    }

    /**
     * Create a team object.
     *
     * @param string $name
     *
     * @return Team
     */
    public function createTeam($name)
    {
        // @todo inject name
        $object = new TeamData('');

        return $object;
    }

    /**
     * Create a SprintMember.
     *
     * @return SprintMember
     */
    public function createSprintMember()
    {
        $teamMember = $this->createTeamMember(new NullSprinter(), new NullTeam());

        return new SprintMemberData(0, 0, $this->createSprint(''), $teamMember);
    }

    /**
     * Create a Sprinter.
     *
     * @param string $name
     *
     * @return Sprinter
     */
    public function createSprinter($name)
    {
        return new SprinterData($name);
    }

    /**
     * Create a TeamMember.
     *
     * @param Sprinter $sprinter
     * @param Team     $team
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team)
    {
        return new TeamMemberData($sprinter, $team);
    }
}
