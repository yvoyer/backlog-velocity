<?php

namespace Star\Component\Sprint;

use Star\Component\Identity\Exception\EntityNotFoundException;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ProjectName;

final class ProjectAggregateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Backlog
     */
    private $backlog;

    public function setUp()
    {
        $this->backlog = Backlog::emptyBacklog();
    }

    public function test_it_should_create_a_project()
    {
        $this->assertCount(0, $this->backlog->projects());

        $project = $this->backlog->createProject(ProjectId::fromString('id'), new ProjectName('name'));
        $this->assertInstanceOf(Project::class, $project);

        $this->assertCount(1, $this->backlog->projects());
        $this->assertContainsOnlyInstancesOf(ProjectId::class, $this->backlog->projects());

        $this->assertCount(1, $events = $project->uncommittedEvents());
        /**
         * @var $event ProjectWasCreated
         */
        $this->assertInstanceOf(ProjectWasCreated::class, $event = $events[0]);
        $this->assertSame('id', $event->projectId()->toString());
        $this->assertSame('name', $event->projectName()->toString());
        $this->assertSame(1, $event->version());
    }

    public function test_it_should_urlize_project_id() {
        $project = $this->backlog->createProject(ProjectId::fromString('  Some LONG String    '), new ProjectName('name'));
        $this->assertSame('some-long-string', $project->getIdentity()->toString());
    }
}
