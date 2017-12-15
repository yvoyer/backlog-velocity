<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\TeamMember;
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
     * @param TeamMember $member
     */
    public function visitTeamMember(TeamMember $member)
    {
        $this->members[$member->memberId()->toString()] = $member->memberId();
    }
}
