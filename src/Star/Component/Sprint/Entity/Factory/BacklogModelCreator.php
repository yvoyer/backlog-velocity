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
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;

/**
 * Class that handle the creation of all objects used by the project.
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class BacklogModelCreator implements EntityCreator
{
    /**
     * Create a sprint object.
     *
     * @param string  $name
     * @param string  $teamName
     *
     * @return Sprint
     */
    public function createSprint($name, $teamName)
    {
        $object = new SprintData($name, new TeamData($teamName));

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
        $object = new TeamModel($name);

        return $object;
    }

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
    public function createSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        throw new \RuntimeException(__METHOD__ . ' Not implemented yet');
        return new SprintMemberData($availableManDays, $actualVelocity, $sprint, $teamMember);
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
        return new PersonModel($name);
    }

    /**
     * Create a TeamMember.
     *
     * @param Sprinter $sprinter
     * @param Team     $team
     * @param integer  $availableManDays
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team, $availableManDays)
    {
        throw new \RuntimeException(__METHOD__ . ' Not implemented yet');
//        $teamMember = new TeamMemberModel($sprinter, $team);
//        $teamMember->setAvailableManDays($availableManDays);
//
//        return $teamMember;
    }
}
