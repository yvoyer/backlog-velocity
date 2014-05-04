<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullEntityCreator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null
 */
class NullEntityCreator implements EntityCreator
{
    /**
     * Create a sprint object.
     *
     * @param string $name
     * @param string $teamName
     *
     * @return Sprint
     */
    public function createSprint($name, $teamName)
    {
        throw new \RuntimeException('Method createSprint() not implemented yet.');
    }

    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
     */
    public function createTeam($name)
    {
        throw new \RuntimeException('Method createTeam() not implemented yet.');
    }

    /**
     * Create a SprintMember.
     *
     * @param integer $availableManDays @todo Remove arg, use $teamMember value
     * @param integer $actualVelocity
     * @param Sprint $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    public function createSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        throw new \RuntimeException('Method createSprintMember() not implemented yet.');
    }

    /**
     * Create a Sprinter.
     *
     * @param string $name The name of the sprinter.
     *
     * @return Sprinter
     */
    public function createSprinter($name)
    {
        throw new \RuntimeException('Method createSprinter() not implemented yet.');
    }

    /**
     * Create a TeamMember.
     *
     * @param Sprinter $sprinter
     * @param Team $team
     * @param integer $availableManDays
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team, $availableManDays)
    {
        throw new \RuntimeException('Method createTeamMember() not implemented yet.');
    }
}
 