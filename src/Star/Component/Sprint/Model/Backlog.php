<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\EstimatedFocusCalculator;
use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;

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
        $this->sprints[$sprintName] = new SprintModel(
            $sprintName,
            $team
        );
    }

    public function startSprint($sprintName)
    {
        $sprint = $this->getSprint($sprintName);
        $sprint->start();
    }

    /**
     * @param string $sprintName
     *
     * @return Sprint
     *
     * todo Should be private
     */
    public function getSprint($sprintName)
    {
        if (false === array_key_exists($sprintName, $this->sprints)) {
            throw new \Exception('Sprint not found ' . $sprintName);
        }

        return $this->sprints[$sprintName];
    }

    /**
     * @param string $teamName
     *
     * @return Team
     *
     * todo Should be private
     */
    public function getTeam($teamName)
    {
        return $this->teams[$teamName];
    }

    /**
     * @param string $name
     *
     * @return Person
     */
    private function getPerson($name)
    {
        return $this->persons[$name];
    }

    public function estimateVelocity($sprintName, ResourceCalculator $calculator)
    {
        $sprint = $this->getSprint($sprintName);

        return $sprint->estimateVelocity($calculator);
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
}
 