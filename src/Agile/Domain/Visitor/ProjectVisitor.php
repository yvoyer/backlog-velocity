<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Visitor;

use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\TeamMember;

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
