<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class AllProjectsHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_should_return_no_projects()
    {
        $result = $this->handle(
            new AllProjectsHandler($this->connection),
            new AllProjects()
        );
        $this->assertCount(0, $result);
    }

    public function test_it_should_return_all_projects()
    {
        $this->createProject('p1');
        $this->createProject('p2');
        $this->createProject('p3');

        /**
         * @var ProjectDTO[] $result
         */
        $result = $this->handle(
            new AllProjectsHandler($this->connection),
            new AllProjects()
        );
        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(ProjectDTO::class, $result);

        $this->assertSame('p1', $result[0]->id);
        $this->assertSame('p1', $result[0]->name);
        $this->assertSame('p2', $result[1]->id);
        $this->assertSame('p2', $result[1]->name);
        $this->assertSame('p3', $result[2]->id);
        $this->assertSame('p3', $result[2]->name);
    }

    public function test_it_should_order_in_alphabetical_order()
    {
        $this->createProject('p2');
        $this->createProject('p1');
        $this->createProject('p3');

        /**
         * @var ProjectDTO[] $result
         */
        $result = $this->handle(
            new AllProjectsHandler($this->connection),
            new AllProjects()
        );
        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(ProjectDTO::class, $result);

        $this->assertSame('p1', $result[0]->id);
        $this->assertSame('p1', $result[0]->name);
        $this->assertSame('p2', $result[1]->id);
        $this->assertSame('p2', $result[1]->name);
        $this->assertSame('p3', $result[2]->id);
        $this->assertSame('p3', $result[2]->name);
    }

    protected function doFixtures()
    {
    }
}
