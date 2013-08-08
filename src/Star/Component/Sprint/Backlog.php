<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

/**
 * Class Backlog
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class Backlog
{
    /**
     * @var Repository
     */
    private $sprintRepository;

    /**
     * @var Repository
     */
    private $teamRepository;

    /**
     * @param Repository $sprintRepository
     * @param Repository $teamRepository
     */
    public function __construct(Repository $sprintRepository, Repository $teamRepository)
    {
        $this->sprintRepository = $sprintRepository;
        $this->teamRepository   = $teamRepository;
    }

    /**
     * Add the $sprint.
     *
     * @param \Star\Component\Sprint\Sprint $sprint
     */
    public function addSprint(Sprint $sprint)
    {
        $this->sprintRepository->add(new StubIdentifier($sprint->getName()), $sprint);
    }

    /**
     * Create a sprint.
     *
     * @param string $sprintName
     *
     * @return \Star\Component\Sprint\Sprint
     */
    public function createSprint($sprintName)
    {
        $sprint = new Sprint($sprintName);

        return $sprint;
    }

    /**
     * Search for the $sprintName.
     *
     * @param string $sprintName
     *
     * @return null|\Star\Component\Sprint\Sprint
     */
    public function findSprint($sprintName)
    {
        return $this->sprintRepository->find(new StubIdentifier($sprintName));
    }

    /**
     * Return the found or newly created $sprintName.
     *
     * @param string $sprintName
     *
     * @return Sprint
     */
    private function getSprint($sprintName)
    {
        $sprint = $this->findSprint($sprintName);
        if (null === $sprint) {
            $sprint = $this->createSprint($sprintName);
        }

        return $sprint;
    }

    /**
     * Returns the sprint collection.
     *
     * @return Sprint[]
     */
    public function getSprints()
    {
        return $this->sprintRepository->findAll();
    }

    /**
     * Add the $team to the collection.
     *
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this->teamRepository->add(new StubIdentifier($team->getName()), $team);
    }

    /**
     * Create a team.
     *
     * @param string $name
     *
     * @return \Star\Component\Sprint\Team
     */
    public function createTeam($name)
    {
        $team = new Team($name);

        return $team;
    }

    /**
     * Find the team with $teamName.
     *
     * @param string $teamName
     *
     * @return null|Team
     */
    public function findTeam($teamName)
    {
        return $this->teamRepository->find(new StubIdentifier($teamName));
    }

    /**
     * Returns the found or newly created $teamName.
     *
     * @param string $teamName
     *
     * @return null|Team
     */
    private function getTeam($teamName)
    {
        $team = $this->findTeam($teamName);
        if (null === $team) {
            $team = $this->createTeam($teamName);
        }

        return $team;
    }

    /**
     * Returns the team collection.
     *
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teamRepository->findAll();
    }

    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param integer $availableManDays The available man days for the sprint
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays)
    {
        $focus = 70;
        $sprints = $this->sprintRepository->findAll();
        if (false === empty($sprints)) {
            $focus = $this->getEstimatedFocusFactor();
        }

        return (int) floor(($availableManDays * $focus) / 100);
    }

    /**
     * Returns the estimated focus factor based on past sprints.
     *
     * @return float
     */
    private function getEstimatedFocusFactor()
    {
        $aPastFocus = array();
        foreach ($this->sprintRepository->findAll() as $sprint) {
            $aPastFocus[] = $sprint->getFocusFactor();
        }

        return $this->calculateAverage($aPastFocus);
    }

    /**
     * Calculate the average of $values in array.
     *
     * @param array $values
     *
     * @return float
     */
    private function calculateAverage(array $values)
    {
        $total = 0;
        $count = count($values); //total numbers in array
        foreach ($values as $value) {
            $total = $total + $value; // total value of array numbers
        }
        $average = ($total/$count); // get average value

        return $average;
    }

    /**
     * Add a $teamName to the $sprintName.
     *
     * @param string $teamName
     * @param string $sprintName
     *
     * @return Team
     */
    public function addTeamToSprint($teamName, $sprintName)
    {
        $team   = $this->createTeam($teamName);
        $sprint = $this->getSprint($sprintName);
        $this->addSprint($sprint);
        $this->addTeam($team);
    }
}
