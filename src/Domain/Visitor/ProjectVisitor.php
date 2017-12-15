<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\TeamMember;

interface ProjectVisitor
{
    /**
     * @param Project $project
     */
    public function visitProject(Project $project);

    /**
     * @param TeamMember $member
     */
    public function visitTeamMember(TeamMember $member);
}
