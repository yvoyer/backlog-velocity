<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Visitor\TeamMembersInProject;

final class BacklogBuilderTest extends TestCase
{
    public function test_it_should_create_a_project()
    {
        $project = BacklogBuilder::createProject('p1')
            ->withTeam('t1')
            ->joinedByMembers(['m1', 'm2', 'm3'])
            ->endTeam()
            ->withTeam('t2')
            ->joinedByMember('m2')
            ->getProject();
        $this->assertInstanceOf(Project::class, $project);

        $this->assertSame('p1', $project->name()->toString());
        $this->assertCount(0, $project->sprints());
        $this->assertCount(2, $project->teams());
        $project->acceptProjectVisitor($members = new TeamMembersInProject());
        $this->assertCount(3, $members->getMembers());
    }

    public function test_it_should_create_sprint()
    {
        $sprint = SprintBuilder::pending('s1', 'p1', 't1')
            ->committedMember('m1', 2)
            ->committedMember('m3', 4)
            ->started(10, '2001-02-01')
            ->closed(15, '2002-02-01')
            ->buildSprint();

        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertTrue($sprint->isClosed());
        $this->assertFalse($sprint->isStarted());
        $this->assertSame('2001-02-01', $sprint->startedAt()->format('Y-m-d'));
        $this->assertSame('2002-02-01', $sprint->endedAt()->format('Y-m-d'));
        $this->assertSame('s1', $sprint->getName()->toString());
        $this->assertSame('p1', $sprint->projectId()->toString());
        $this->assertSame(6, $sprint->getManDays()->toInt());
        $this->assertSame(10, $sprint->getEstimatedVelocity());
        $this->assertSame(15, $sprint->getActualVelocity());
        $this->assertSame(250, $sprint->getFocusFactor());
    }
}
