<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class EntityCreator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
interface EntityCreator
{
    /**
     * Create a sprint object.
     *
     * @param string  $name
     * @param Team    $team
     * @param integer $manDays
     *
     * @return Sprint
     */
    public function createSprint($name, Team $team, $manDays);

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
     * @param integer    $availableManDays
     * @param integer    $actualVelocity
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    public function createSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember);

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
     * @param Sprinter $sprinter
     * @param Team     $team
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team);
}
