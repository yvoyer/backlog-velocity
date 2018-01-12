<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

/**
 * @group functional
 */
final class MostActiveSprintInProjectHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_should_return_no_active_sprint_on_empty_project()
    {
        $this->assertSprintNotFound('empty');
    }

    public function test_it_should_return_pending_sprint_on_project_with_one_pending_sprint()
    {
        $result = $this->execute($projectId = 'p1');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame('pending-alone', $result->id);
        $this->assertSame('pending-alone', $result->name);
        $this->assertSame('p1', $result->project->id);
        $this->assertSame('p1', $result->project->name);
        $this->assertSame('t1', $result->team->id);
        $this->assertSame('t1', $result->team->name);
        $this->assertSame(0, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame('pending', $result->status());
        $this->assertFalse($result->hasCommitments());
        $this->assertTrue($result->isPending());
        $this->assertInstanceOf(\DateTimeInterface::class, $result->createdAt());
    }

    public function test_it_should_return_started_sprint_on_project_with_one_started_sprint()
    {
        $result = $this->execute($projectId = 'p2');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame('started-alone', $result->id);
        $this->assertSame('started-alone', $result->name);
        $this->assertSame('p2', $result->project->id);
        $this->assertSame('p2', $result->project->name);
        $this->assertSame('t1', $result->team->id);
        $this->assertSame('t1', $result->team->name);
        $this->assertSame(76, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame('started', $result->status());
        $this->assertTrue($result->hasCommitments());
        $this->assertInstanceOf(\DateTimeInterface::class, $result->startedAt());
    }

    public function test_it_should_return_no_sprint_on_project_with_one_closed_sprint()
    {
        $this->assertSprintNotFound('p3');
    }

    public function test_it_should_return_pending_sprint_on_project_with_pending_and_closed_sprint()
    {
        $result = $this->execute($projectId = 'p4');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame('pending-not-alone', $result->id);
        $this->assertSame('pending-not-alone', $result->name);
        $this->assertSame('p4', $result->project->id);
        $this->assertSame('p4', $result->project->name);
        $this->assertSame('t1', $result->team->id);
        $this->assertSame('t1', $result->team->name);
        $this->assertSame(0, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame('pending', $result->status());
        $this->assertFalse($result->hasCommitments());
    }

    public function test_it_should_return_started_sprint_on_project_with_started_and_closed_sprint()
    {
        $result = $this->execute($projectId = 'p5');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame('started-not-alone', $result->id);
        $this->assertSame('started-not-alone', $result->name);
        $this->assertSame('p5', $result->project->id);
        $this->assertSame('p5', $result->project->name);
        $this->assertSame('t1', $result->team->id);
        $this->assertSame('t1', $result->team->name);
        $this->assertSame(76, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame('started', $result->status());
        $this->assertTrue($result->hasCommitments());
    }

    public function test_it_should_return_no_sprint_sprint_on_project_with_two_closed_sprint()
    {
        $this->assertSprintNotFound('p6');
    }

    protected function doFixtures()
    {
        $this->createTeam('t1');
        $this->createProject('empty');

        $p1 = $this->createProject('p1');
        $this->createPendingSprint('pending-alone', $p1, 't1');

        $p2 = $this->createProject('p2');
        $this->createStartedSprint('started-alone', $p2, 't1');

        $p3 = $this->createProject('p3');
        $this->createClosedSprint('closed-alone', $p3, 't1');

        $p4 = $this->createProject('p4');
        $this->createPendingSprint('pending-not-alone', $p4, 't1');

        $p5 = $this->createProject('p5');
        $this->createStartedSprint('started-not-alone', $p5, 't1');

        $p6 = $this->createProject('p6');
        $this->createClosedSprint('closed-not-alone', $p6, 't1');
    }

    /**
     * @param string $projectId
     *
     * @return SprintDTO
     */
    private function execute(string $projectId) :SprintDTO
    {
        /**
         * @var SprintDTO|null $result
         */
        return $this->handle(
            new MostActiveSprintInProjectHandler($this->connection),
            new MostActiveSprintInProject(ProjectId::fromString($projectId))
        );
    }

    private function assertSprintNotFound(string $projectId)
    {
        /**
         * @var SprintDTO|null $result
         */
        $result = $this->handle(
            new MostActiveSprintInProjectHandler($this->connection),
            new MostActiveSprintInProject(ProjectId::fromString($projectId))
        );

        $this->assertNull($result);
    }
}
