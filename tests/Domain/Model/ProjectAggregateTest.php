<?php

namespace Star\Component\Sprint\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Event\PersonJoinedTeam;
use Star\Component\Sprint\Domain\Event\ProjectWasCreated;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

final class ProjectAggregateTest extends TestCase
{
    /**
     * @var ProjectAggregate
     */
    private $project;

    public function setUp()
    {
        $this->project = ProjectAggregate::emptyProject(ProjectId::fromString('id'), new ProjectName('name'));
    }

    public function test_it_should_create_a_project()
    {
        $this->assertCount(1, $events = $this->project->uncommittedEvents());
        /**
         * @var $event ProjectWasCreated
         */
        $this->assertInstanceOf(ProjectWasCreated::class, $event = $events[0]);
        $this->assertSame('id', $event->projectId()->toString());
        $this->assertSame('name', $event->projectName()->toString());
        $this->assertSame(1, $event->version());
    }

    public function test_it_should_urlize_project_id() {
        $project = ProjectAggregate::emptyProject(
            ProjectId::fromString('  Some LONG String    '), new ProjectName('name')
        );
        $this->assertSame('some-long-string', $project->getIdentity()->toString());
    }

    /**
     * @expectedException \Star\Component\Sprint\Domain\Exception\EntityNotFoundException
     * @expectedExceptionMessage dsadsadsa
     */
    public function test_it_should_throw_exception_when_team_not_found()
    {
        ProjectAggregate::fromStream(
            [
                PersonJoinedTeam::version1(
                    ProjectId::uuid(),
                    MemberId::fromString('m1'),
                    TeamId::fromString('t1')
                )
            ]
        );
    }
}
