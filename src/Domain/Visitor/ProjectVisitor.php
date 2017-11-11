<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Team;

interface ProjectVisitor
{
    /**
     * @param Project $project
     */
    public function visitProject(Project $project);

    /**
     * @param Team $team
     */
    public function visitTeam(Team $team);

    /**
     * @param Person $member
     */
    public function visitTeamMember(Person $member);
}