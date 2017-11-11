<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;

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
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        $visitor->visitTeam($this);
        foreach ($this->teamMembers as $teamMember) {
            $teamMember->acceptProjectVisitor($visitor);
        }
    }

    /**
     * @param PersonId $personId
     *
     * @throws \Star\Component\Sprint\Domain\Exception\InvalidArgumentException
     * @return bool
     */
    private function hasTeamMember(PersonId $personId)
    {
        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($personId) {
            return $member->matchPerson($personId);
        });
    }

    /**
     * @param Person $person
     *
     * @throws \Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException
     *
     * @return TeamMember
     */
    public function addTeamMember(Person $person)
    {
        if ($this->hasTeamMember($person->getId())) {
            throw new EntityAlreadyExistsException("Person '{$person->getName()->toString()}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $person);
        $this->teamMembers[] = $teamMember;

        return $teamMember;
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
