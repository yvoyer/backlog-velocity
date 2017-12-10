<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Identity\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Port\SprintDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class SprintWithIdentityHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_should_return_pending_sprint()
    {
        $result = $this->execute($status = 'pending');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame($status, $result->id);
        $this->assertSame($status, $result->name);
        $this->assertSame('p1', $result->projectId);
        $this->assertSame(0, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame($status, $result->status());
        $this->assertSame($status, $result->projectId);
        $this->assertSame('TODO', $result->teamId);
    }

    public function test_it_should_return_started_sprint()
    {
        $result = $this->execute($status = 'started');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame($status, $result->id);
        $this->assertSame($status, $result->name);
        $this->assertSame('p1', $result->projectId);
        $this->assertSame(76, $result->estimatedVelocity);
        $this->assertSame(0, $result->actualVelocity);
        $this->assertSame($status, $result->status());
        $this->assertSame('TODO', $result->teamId);
    }

    public function test_it_should_return_closed_sprint()
    {
        $result = $this->execute($status = 'closed');

        $this->assertInstanceOf(SprintDTO::class, $result);
        $this->assertSame($status, $result->id);
        $this->assertSame($status, $result->name);
        $this->assertSame('p1', $result->projectId);
        $this->assertSame(98, $result->estimatedVelocity);
        $this->assertSame(10, $result->actualVelocity);
        $this->assertSame($status, $result->status());
        $this->assertSame('TODO', $result->teamId);
    }

    public function test_it_should_throw_exception_when_not_found()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            "Object of class 'Star\Component\Sprint\Domain\Model\SprintModel' with identity 'not-found' could not be found."
        );

        $this->handle(
            new SprintWithIdentityHandler($this->connection),
            SprintWithIdentity::fromString('not-found')
        );
    }

    public function test_it_should_return_the_sprint_context_project_information()
    {
        $this->fail('TODO project based');
    }

    public function test_it_should_return_the_sprint_context_team_information()
    {
        $this->fail('TODO team based');
    }

    protected function doFixtures()
    {
        $project = $this->createProject('p1');
        $this->createPendingSprint('pending', $project);
        $this->createStartedSprint('started', $project);
        $this->createClosedSprint('closed', $project);
    }

    /**
     * @param string $sprintId
     *
     * @return SprintDTO
     */
    private function execute(string $sprintId): SprintDTO
    {
        $result = $this->handle(
            new SprintWithIdentityHandler($this->connection),
            SprintWithIdentity::fromString($sprintId)
        );
        return $result;
    }
}
