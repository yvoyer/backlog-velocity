<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamMemberCollection;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Exception\InvalidArgumentException;

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
     * @var Collection|TeamMember[]
     */
    private $teamMembers;

    /**
     * @var Collection|Sprint[]
     */
    private $sprints;

    /**
     * @param TeamId $id
     * @param TeamName $name
     */
    public function __construct(TeamId $id, TeamName $name)
    {
        $this->id = $id->toString();
        $this->name = $name->toString();
        $this->teamMembers = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    /**
     * Returns the unique id.
     *
     * @return TeamId
     */
    public function getId()
    {
        return TeamId::fromString($this->id);
    }

    /**
     * Returns the Name.
     *
     * @return string
     */
    public function getName()
    {
// todo       return new TeamName($this->name);
        return $this->name;
    }

    /**
     * @param string $personName
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return bool
     */
    private function hasTeamMember($personName)
    {
        if (false === is_string($personName)) {
            throw new InvalidArgumentException('The person name must be string.');
        }

        return (bool) $this->getTeamMember($personName);
    }

    /**
     * @param Person $person
     *
     * @throws \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     * @return TeamMember
     */
    public function addTeamMember(Person $person)
    {
        $name = $person->getName();

        if ($this->hasTeamMember($name)) {
            throw new EntityAlreadyExistsException("Person '{$name}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $person);
        $this->teamMembers->add($teamMember);

        return $teamMember;//$this->getTeamMember($name);
    }

    /**
     * @param string $memberName
     *
     * @return TeamMember|null
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    private function getTeamMember($memberName)
    {
        $memberList = new TeamMemberCollection($this->getTeamMembers());

        return $memberList->findOneByName($memberName);
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getTeamMembers()
    {
        return $this->teamMembers->toArray();
    }

    /**
     * Returns the closed sprints
     *
     * @return \Star\Component\Sprint\Entity\Sprint[]
     */
    public function getClosedSprints()
    {
        $closedSprint = array();
        foreach ($this->sprints as $sprint) {
            if ($sprint->isClosed()) {
                $closedSprint[] = $sprint;
            }
        }

        return $closedSprint;
    }

    /**
     * @param string $name
     *
     * @throws \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     * @return Sprint
     */
    public function createSprint($name)
    {
        if ($this->hasSprint($name)) {
            throw new EntityAlreadyExistsException("The sprint '{$name}' already exists for the team.");
        }

        $sprint = new SprintModel(new SprintId(), $name, $this);
        $this->sprints->add($sprint);

        return $sprint;
    }

    /**
     * @param string $sprintName
     *
     * @return bool
     */
    private function hasSprint($sprintName)
    {
        if ($this->getSprint($sprintName)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sprintName
     *
     * @return Sprint|null
     */
    private function getSprint($sprintName)
    {
        $collection = new SprintCollection($this->sprints->toArray());

        return $collection->findOneByName($sprintName);
    }
}
