<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Builder;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;

final class BacklogBuilderTest extends TestCase
{
    public function test_it_should_create_a_team(): void
    {
        /**
         * @var TeamModel $team
         */
        $team = BacklogBuilder::createTeam('p1', 't1')
            ->joinedByMembers(['m1', 'm2', 'm3'])
            ->getTeam();
        $this->assertInstanceOf(TeamModel::class, $team);

        $this->assertSame('t1', $team->getName()->toString());
        $this->assertCount(0, $team->sprints());
        $this->assertCount(3, $team->members());
    }

    public function test_it_should_create_a_project(): void
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
    }

    public function test_it_should_create_sprint(): void
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
        $this->assertSame(10, $sprint->getPlannedVelocity()->toInt());
        $this->assertSame(15, $sprint->getActualVelocity()->toInt());
        $this->assertSame(150, $sprint->getFocusFactor()->toInt());
    }
}
