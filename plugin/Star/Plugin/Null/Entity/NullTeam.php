<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class NullTeam
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Entity
 */
class NullTeam implements Team
{
    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param Person $person
     *
     * @return TeamMember
     */
    public function addTeamMember(Person $person)
    {
        return new NullTeamMember();
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        // Do nothing
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        // Do nothing
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getTeamMembers()
    {
        return array();
    }

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return false;
    }

    /**
     * Returns the team available man days.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        return 0;
    }

    /**
     * Returns the list of closed sprints.
     *
     * @return Sprint[]
     */
    public function getClosedSprints()
    {
        return array();
    }

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

//    /**
//     * @param Sprint $sprint
//     * @param VelocityCalculator $calculator
//     *
//     * @return Sprint
//     */
//    public function startSprint(Sprint $sprint, VelocityCalculator $calculator)
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }

    /**
     * @param string $sprintName
     * @param int $actualVelocity
     *
     * @return Sprint
     */
//    public function closeSprint($sprintName, $actualVelocity)
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }

    /**
     * @return integer
     */
    public function getActualVelocity()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Person $person
     * @param Sprint $sprint
     * @param int $manDays
     *
     * @return SprintMember
     */
//    public function addSprintMember(Person $person, Sprint $sprint, $manDays)
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }
}
