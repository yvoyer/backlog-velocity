<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Collection\TeamMemberCollection;
use Star\Component\Sprint\Entity\Id\TeamId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
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
     * @var SprintMemberCollection
     */
    private $sprinters;

    /**
     * @param string $name
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function __construct($name)
    {
        if (empty($name)) {
            // todo create NameCantBeEmptyException
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->id = new TeamId($name);
        $this->name = $name;
        $this->members = new TeamMemberCollection();
        $this->sprints = new SprintCollection();
        $this->sprinters = new SprintMemberCollection();
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
        foreach ($this->sprinters as $sprintMember) {
            $manDays += $sprintMember->getAvailableManDays();
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
        if (empty($this->name)) {
            return false;
        }

        return true;
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
     * @param Sprint $sprint
     * @param VelocityCalculator $calculator
     *
     * @return Sprint
     */
    public function startSprint(Sprint $sprint, VelocityCalculator $calculator)
    {
        $this->guardAgainstNoMemberInTeam();

        // todo guard against no sprinter
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
            // todo create TeamMemberNotFoundException
            throw new InvalidArgumentException("The team member '{$memberName}' was not found.");
        }

        return $member;
    }

    private function guardAgainstNoMemberInTeam()
    {
        if (0 === count($this->members)) {
            // todo create TeamNeedAtLeastOneTeamMember
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
            // todo SprintNotFoundException
            throw new InvalidArgumentException("The sprint '{$sprintName}' was not found.");
        }

        return $sprint;
    }

    /**
     * @param Person $person
     * @param Sprint $sprint
     * @param int $manDays
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return SprintMember
     */
    public function addSprintMember(Person $person, Sprint $sprint, $manDays)
    {
        if ($sprint->isStarted()) {
            $sprintName = $sprint->getName();
            // todo create AlreadyStartedSprintException
            throw new InvalidArgumentException("The sprint '{$sprintName}' is already started, no sprint member can be added.");
        }

        $name = $person->getName();
        if (false === $this->hasPerson($person)) {
            // todo create NotMemberOfTeamException
            throw new InvalidArgumentException("The person '{$name}' is not member of team.");
        }

        if ($this->sprinters->findOneByName($name)) {
            // todo create AlreadyAddedSprintMemberException
            throw new InvalidArgumentException("The sprint member '{$name}' is already added.");
        }

        $sprinter = new SprinterModel($sprint, $person, $manDays);
        $this->sprinters->addSprinter($sprinter);

        return $sprinter;
    }

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name)
    {
        return new SprintModel($name, $this);
    }
}
 