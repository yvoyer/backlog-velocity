<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Plugin\Null\Entity\NullTeam;

/**
 * Class Backlog
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 *
 * @todo Move someplace else
 */
class Backlog
{
    private $sprints = array();
    private $sprinters = array();
    private $teams = array();
    private $teamMembers = array();
    private $persons = array();

    public function createPerson($name)
    {
        $this->persons[$name] = new PersonModel($name);
    }

    public function createTeam($teamName)
    {
        $this->teams[$teamName] = new TeamModel($teamName);
    }

    public function createSprint($sprintName, $teamName)
    {
        $team = $this->getTeam($teamName);
        $this->sprints[$sprintName] = new SprintModel($sprintName, $team);
    }

    public function startSprint($sprintName)
    {
        $sprint = $this->getSprint($sprintName);
        $sprint->start();
    }

    public function closeSprint($sprintName, $actualVelocity)
    {
        $sprint = $this->getSprint($sprintName);
        $sprint->close($actualVelocity);
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
}
 