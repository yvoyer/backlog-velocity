<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Plugin\Null\Entity\NullTeam;

/**
 * Class Backlog
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 *
 * @todo Move someplace else
 * @deprecated Do not use anymore
 */
class Backlog implements EntityCreator
{
    /**
     * @var Sprint[]
     */
    private $sprints = array();

    /**
     * @var Sprinter[]
     */
    private $sprinters = array();

    /**
     * @var Team[]
     */
    private $teams = array();

    /**
     * @var TeamMember[]
     */
    private $teamMembers = array();

    /**
     * @var Person[]
     */
    private $persons = array();

    public function __construct()
    {
        $this->persons = new PersonCollection();
    }

    /**
     * @param string $name
     *
     * @return Person
     */
    public function createPerson($name)
    {
        $this->persons[$name] = new PersonModel($name);

        return $this->persons[$name];
    }

    /**
     * @param string $teamName
     *
     * @return Team
     */
    public function createTeam($teamName)
    {
        $this->teams[$teamName] = new TeamModel($teamName);

        return $this->teams[$teamName];
    }

    /**
     * @param string $sprintName
     * @param string $teamName
     *
     * @return Sprint
     */
    public function createSprint($sprintName, $teamName)
    {
        $team = $this->getTeam($teamName);
        $this->sprints[$sprintName] = new SprintModel($sprintName, $team);

        return $this->sprints[$sprintName];
    }

    /**
     * @param string  $sprintName
     *
     * @return Sprint
     */
    public function getSprint($sprintName)
    {
        $this->guardAgainstSprintNotFound($sprintName);

        return $this->sprints[$sprintName];
    }

    /**
     * @param string $teamName
     *
     * @return Team
     */
    public function getTeam($teamName)
    {
        $this->guardAgainstTeamNotFound($teamName);

        return $this->teams[$teamName];
    }

    /**
     * @param string $name
     *
     * @return Person
     */
    public function getPerson($name)
    {
        $this->guardAgainstPersonNotFound($name);

        return $this->persons[$name];
    }

    public function estimateVelocity($sprintName, ResourceCalculator $calculator)
    {
        $sprint = $this->getSprint($sprintName);

        return $calculator->calculateEstimatedVelocity($sprint);
    }

    /**
     * @param $teamName
     * @param $personName
     * todo rename joinTeam
     */
    public function addTeamMember($teamName, $personName)
    {
        $this->teamMembers[] = new TeamMemberModel(
            $this->getTeam($teamName),
            $this->getPerson($personName),
            -1
        );
    }

    /**
     * @param $sprintName
     * @param $personName
     * @param $availableManDays
     *
     * todo rename joinSprint
     */
    public function addSprinter($sprintName, $personName, $availableManDays)
    {
        $sprint = $this->getSprint($sprintName);
        $this->sprinters[] = $sprint->addSprinter(
            $this->getPerson($personName),
            $availableManDays
        );
    }

    /**
     * @return Sprinter[]
     */
    public function getSprinters()
    {
        return $this->sprinters;
    }

    /**
     * @param string $sprintName
     * @throws \Exception
     */
    private function guardAgainstSprintNotFound($sprintName)
    {
        if (false === array_key_exists($sprintName, $this->sprints)) {
            throw new \Exception('Sprint not found ' . $sprintName);
        }
    }

    /**
     * @param string $teamName
     * @throws \Exception
     */
    private function guardAgainstTeamNotFound($teamName)
    {
        if (false === array_key_exists($teamName, $this->teams)) {
            throw new \Exception('Team not found ' . $teamName);
        }
    }

    /**
     * @param string $personName
     * @throws \Exception
     */
    private function guardAgainstPersonNotFound($personName)
    {
        if (false === array_key_exists($personName, $this->persons)) {
            throw new \Exception('Person not found ' . $personName);
        }
    }

    /**
     * @param string $sprinterName
     * @throws \Exception
     */
    private function guardAgainstSprinterNotFound($sprinterName)
    {
        if (false === array_key_exists($sprinterName, $this->sprinters)) {
            throw new \Exception('Sprinter not found ' . $sprinterName);
        }
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
        throw new \RuntimeException('Method ' . __CLASS__ . '::createSprintMember() not implemented yet.');
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
        throw new \RuntimeException('Method ' . __CLASS__ . '::createSprinter() not implemented yet.');
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
        throw new \RuntimeException('Method ' . __CLASS__ . '::createTeamMember() not implemented yet.');
    }
}
 