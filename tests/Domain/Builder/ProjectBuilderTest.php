<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Visitor\TeamMembersInProject;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Plugin\Null\Entity\NullPerson;

final class ProjectBuilderTest extends TestCase
{
    public function test_it_should_create_project()
    {
        $project = ProjectBuilder::projectIsCreated('P 1')->getProjectId();

        $this->assertInstanceOf(ProjectAggregate::class, $project);
        $this->assertSame('p-1', $project->getIdentity()->toString());
        $this->assertSame('P 1', $project->name()->toString());
    }

    public function test_it_should_create_sprint()
    {
        $project = ProjectBuilder::projectIsCreated('p1')
            ->withPendingSprint('s1', 'Sprint 1')
            ->getProjectId();
        $this->assertInstanceOf(ProjectAggregate::class, $project);
        $this->assertCount(1, $project->sprints());
        $this->assertContainsOnlyInstancesOf(SprintId::class, $project->sprints());
    }

    public function test_it_should_register_person_in_team()
    {
        $project = ProjectBuilder::projectIsCreated('p1')
            ->withTeam('team')
            ->withMemberInTeam(new NullPerson(), 'team')
            ->getProjectId();

        $this->assertInstanceOf(ProjectAggregate::class, $project);
        $this->assertCount(1, $project->teams());
        $this->assertContainsOnlyInstancesOf(TeamId::class, $project->teams());

        $project->acceptProjectVisitor($visitor = new TeamMembersInProject());
        $this->assertCount(1, $visitor->getPersons());
    }
}
