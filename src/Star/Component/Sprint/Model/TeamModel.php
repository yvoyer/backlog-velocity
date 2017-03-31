<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($personName) {
            return $member->getName() === $personName;
        });
    }

    /**
     * @param Person $person
     *
     * @throws \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     */
    public function addTeamMember(Person $person)
    {
        $name = $person->getName();

        if ($this->hasTeamMember($name)) {
            throw new EntityAlreadyExistsException("Person '{$name}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $person);
        $this->teamMembers[] = $teamMember;
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[] todo return PersonId[]
     */
    public function getTeamMembers()
    {
        return $this->teamMembers->toArray();
    }
}
