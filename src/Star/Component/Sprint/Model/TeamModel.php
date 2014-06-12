<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamMemberCollection;
use Star\Component\Sprint\Entity\Id\TeamId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
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
     * @var ArrayCollection|TeamMember[]
     */
    private $teamMembers;

    /**
     * @var ArrayCollection|Sprint[]
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
            // todo create NameCantBeEmptyException
            throw new InvalidArgumentException("The name can't be empty.");
        }

        $this->name = $name;
        $this->teamMembers = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;//(string) new TeamId(strval($this->name));
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
     * @return TeamMember
     */
    public function addTeamMember(Person $person)
    {
        $name = $person->getName();
        if (false === $this->hasTeamMember($name)) {
            $teamMember = new TeamMemberModel($this, $person);
            $this->teamMembers->add($teamMember);
        }

        return $this->getTeamMember($name);
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
     * @return \Star\Component\Sprint\Entity\Sprint[]|SprintCollection
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
     * @return Sprint
     */
    public function createSprint($name)
    {
        $sprint = new SprintModel($name, $this);
        $this->sprints->add($sprint);

        return $sprint;
    }
}
 