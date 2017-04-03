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
use Star\Component\Sprint\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamModel implements Team
{
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
     * @return TeamName
     */
    public function getName()
    {
        return new TeamName($this->name);
    }

    /**
     * @param PersonName $personName
     *
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return bool
     */
    private function hasTeamMember(PersonName $personName)
    {
        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($personName) {
            return $member->matchPerson($personName->toString());
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
            throw new EntityAlreadyExistsException("Person '{$name->toString()}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $person);
        $this->teamMembers[] = $teamMember;
    }

    /**
     * Returns the members of the team.
     *
     * @return TeamMemberDTO[]
     */
    public function getTeamMembers()
    {
        return $this->teamMembers->map(function(TeamMember $member) {
            return $member->teamMemberDto();
        })->getValues();
    }

    /**
     * @param string $id
     * @param string $name
     *
     * @return TeamModel
     */
    public static function fromString($id, $name)
    {
        return new self(TeamId::fromString($id), new TeamName($name));
    }
}
