<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamMemberCollection;
use Star\Component\Sprint\Entity\Id\TeamId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Plugin\Null\Entity\NullTeamMember;

/**
 * Class TeamModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class TeamModel implements Team
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var TeamId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TeamMemberCollection|TeamMember[]
     */
    private $members;

    /**
     * @var SprintCollection|Sprint[]
     */
    private $sprints;

    /**
     * @param string $name
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->id = new TeamId($name);
        $this->name = $name;
        $this->members = new TeamMemberCollection();
        $this->sprints = new SprintCollection();
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the available man days for the team.
     *
     * @return integer
     */
    public function getAvailableManDays()
    {
        $manDays = 0;
        foreach ($this->members as $member) {
            $manDays += $member->getAvailableManDays();
        }

        return $manDays;
    }

    /**
     * @param Person $person
     *
     * @return bool
     */
    public function hasPerson(Person $person)
    {
        foreach ($this->members as $member) {
            if ($member->isEqual($person)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Person $member
     *
     * @return \Star\Component\Sprint\Entity\TeamMember
     */
    public function addMember(Person $person)
    {
        if (false === $this->hasPerson($person)) {
            $teamMember = new TeamMemberModel($this, $person);
            $this->members->addTeamMember($teamMember);

            return $teamMember;
        }

        return new NullTeamMember();
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::toArray() not implemented yet.');
    }

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        throw new \RuntimeException('Method ' . __CLASS__ . '::isValid() not implemented yet.');
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param string $memberName
     * @param int $manDays
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function setAvailability($memberName, $manDays)
    {
        $member = $this->getMember($memberName);
        $member->setAvailableManDays($manDays);
    }

    /**
     * @param string $name
     * @param VelocityCalculator $calculator
     *
     * @return Sprint
     */
    public function startSprint($name, VelocityCalculator $calculator)
    {
        $this->guardAgainstNoMemberInTeam();

        // todo guard against no sprinter
        $sprint = new SprintModel($name, $this);
        $availableManDays = $this->getAvailableManDays();
        $estimatedVelocity = $calculator->calculateEstimatedVelocity($availableManDays, $this->getClosedSprints());

        $sprint->start($estimatedVelocity);
        $this->sprints->add($sprint);

        return $sprint;
    }

    /**
     * @param string $sprintName
     * @param int $actualVelocity
     *
     * @return Sprint
     */
    public function closeSprint($sprintName, $actualVelocity)
    {
        $sprint = $this->getSprint($sprintName);
        // todo check sprint is started
        $sprint->close($actualVelocity);

        return $sprint;
    }

    /**
     * Returns the closed sprints
     *
     * @return \Star\Component\Sprint\Entity\Sprint[]|SprintCollection
     */
    public function getClosedSprints()
    {
        // todo
        return $this->sprints;
    }

    /**
     * @return integer
     */
    public function getActualVelocity()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $memberName
     *
     * @return TeamMember
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    private function getMember($memberName)
    {
        $member = $this->members->findOneByName($memberName);
        if (null === $member) {
            throw new InvalidArgumentException("The team member '{$memberName}' was not found.");
        }

        return $member;
    }

    private function guardAgainstNoMemberInTeam()
    {
        if (0 === count($this->members)) {
            throw new InvalidArgumentException('There should be at least one team member.');
        }
    }

    /**
     * @param $sprintName
     *
     * @return Sprint
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    private function getSprint($sprintName)
    {
        $sprint = $this->sprints->findOneByName($sprintName);
        if (null === $sprint) {
            throw new InvalidArgumentException("The sprint '{$sprintName}' was not found.");
        }

        return $sprint;
    }
}
 