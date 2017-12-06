<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;

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
     * @var Project
     */
    private $project;

    /**
     * @var Collection|TeamMember[]
     */
    private $teamMembers;

    /**
     * @var Collection|Sprint[]
     */
    private $sprints; //todo remove, cross bounded context

    /**
     * @param TeamId $id
     * @param TeamName $name
     * @param Project $project
     */
    public function __construct(TeamId $id, TeamName $name, Project $project)
    {
        $this->id = $id->toString();
        $this->name = $name->toString();
        $this->teamMembers = new ArrayCollection();
        $this->sprints = new ArrayCollection();
        $this->project = $project;
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
     * @param MemberId $memberId
     *
     * @return bool
     */
    private function hasTeamMember(MemberId $memberId) :bool
    {
        return $this->teamMembers->exists(function ($key, TeamMember $member) use ($memberId) {
            return $member->matchPerson($memberId);
        });
    }

    /**
     * @param Member $member
     *
     * @throws \Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException
     *
     * @return TeamMember
     */
    public function addTeamMember(Member $member) :TeamMember
    {
        if ($this->hasTeamMember($member->memberId())) {
            throw new EntityAlreadyExistsException("Person '{$member->memberId()->toString()}' is already part of team.");
        }

        $teamMember = new TeamMemberModel($this, $member->memberId());
        $this->teamMembers[] = $teamMember;

        return $teamMember;
    }

    /**
     * @param PersonId $personId
     *
     * @return TeamMember
     */
    public function joinMember(PersonId $personId): TeamMember
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the members of the team.
     *
     * @return MemberId[]
     */
    public function getTeamMembers() :array
    {
        return $this->teamMembers->map(function(TeamMember $member) {
            return $member->memberId();
        })->getValues();
    }

    /**
     * @param string $id
     * @param string $name
     * @param Project $project
     *
     * @return Team
     */
    public static function fromString(string $id, string $name, Project $project) :Team
    {
        return new self(TeamId::fromString($id), new TeamName($name), $project);
    }
}
