<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class CountSprintsInProjectHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_return_no_sprints_for_empty_project()
    {
        $result = $this->handle(
            new CountSprintsInProjectHandler($this->connection),
            new CountSprintsInProject(ProjectId::fromString('p2'))
        );

        $this->assertSame(0, $result);
    }

    public function test_it_return_sprints_of_project()
    {
        $result = $this->handle(
            new CountSprintsInProjectHandler($this->connection),
            new CountSprintsInProject(ProjectId::fromString('p1'))
        );

        $this->assertSame(3, $result);
    }

    protected function doFixtures()
    {
        $this->createTeam('t1');
        $project = $this->createProject('p1');
        $this->createPendingSprint('pending', $project, 't1');
        $this->createStartedSprint('started', $project, 't1');
        $this->createClosedSprint('closed', $project, 't1');

        $this->createProject('p2');
    }
}
