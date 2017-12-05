<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;

final class TeamMembersInProject implements ProjectVisitor
{
    /**
     * @var MemberId[]
     */
    private $members = [];

    /**
     * @return MemberId[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param Project $project
     */
    public function visitProject(Project $project)
    {
        $this->members = [];
    }

    /**
     * @param Team $team
     */
    public function visitTeam(Team $team)
    {
    }

    /**
     * @param MemberId $member
     */
    public function visitTeamMember(MemberId $member)
    {
        $this->members[$member->toString()] = $member;
    }
}
