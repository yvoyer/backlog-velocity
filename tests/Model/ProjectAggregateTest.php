<?php

namespace Star\Component\Sprint;

use Star\Component\Sprint\Entity\Project;
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

        $project = $this->backlog->createProject(new ProjectId('id'), new ProjectName('name'));
        $this->assertInstanceOf(Project::class, $project);

        $this->assertCount(1, $this->backlog->projects());
        $this->assertInstanceOf(ProjectId::class, $this->backlog->projects());
    }
}
