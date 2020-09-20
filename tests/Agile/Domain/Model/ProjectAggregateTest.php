<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Event\ProjectWasCreated;

final class ProjectAggregateTest extends TestCase
{
    /**
     * @var ProjectAggregate
     */
    private $project;

	protected function setUp(): void
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
}
