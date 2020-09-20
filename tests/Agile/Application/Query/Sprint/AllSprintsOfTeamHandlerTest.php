<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Application\Query\SprintDTO;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

/**
 * @group functional
 */
final class AllSprintsOfTeamHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_return_no_sprints_when_team_has_none(): void
    {
        /**
         * @var SprintDTO[] $result
         */
        $result = $this->handle(
            new AllSprintsOfTeamHandler($this->connection),
            new AllSprintsOfTeam(TeamId::fromString('t1'))
        );
        $this->assertCount(0, $result);
    }

    public function test_it_return_pending_sprints(): void
    {
        /**
         * @var SprintDTO[] $result
         */
        $result = $this->handle(
            new AllSprintsOfTeamHandler($this->connection),
            new AllSprintsOfTeam(TeamId::fromString('t2'))
        );
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(SprintDTO::class, $result);
        $this->assertSame('Pending sprint', $result[0]->name);
        $this->assertTrue($result[0]->isPending());
        $this->assertInstanceOf(\DateTimeInterface::class, $result[0]->createdAt());
    }

    public function test_it_return_started_sprints(): void
    {
        /**
         * @var SprintDTO[] $result
         */
        $result = $this->handle(
            new AllSprintsOfTeamHandler($this->connection),
            new AllSprintsOfTeam(TeamId::fromString('t3'))
        );
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(SprintDTO::class, $result);
        $this->assertSame('Started sprint', $result[0]->name);
        $this->assertTrue($result[0]->isStarted());
        $this->assertInstanceOf(\DateTimeInterface::class, $result[0]->startedAt());
    }

    public function test_it_return_closed_sprints(): void
    {
        /**
         * @var SprintDTO[] $result
         */
        $result = $this->handle(
            new AllSprintsOfTeamHandler($this->connection),
            new AllSprintsOfTeam(TeamId::fromString('t4'))
        );
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(SprintDTO::class, $result);
        $this->assertSame('Closed sprint', $result[0]->name);
        $this->assertTrue($result[0]->isClosed());
        $this->assertInstanceOf(\DateTimeInterface::class, $result[0]->closedAt());
    }

    public function test_it_should_order_by_status(): void
    {
        /**
         * @var SprintDTO[] $result
         */
        $result = $this->handle(
            new AllSprintsOfTeamHandler($this->connection),
            new AllSprintsOfTeam(TeamId::fromString('t5'))
        );
        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(SprintDTO::class, $result);
        $this->assertSame('Sprint pending', $result[0]->name);
        $this->assertTrue($result[0]->isPending());
        $this->assertSame('Sprint started', $result[1]->name);
        $this->assertTrue($result[1]->isStarted());
        $this->assertSame('Sprint closed', $result[2]->name);
        $this->assertTrue($result[2]->isClosed());
    }

    protected function doFixtures(): void
    {
        $projectOne = $this->createProject('p1');
        $this->createTeam('t1');

        $this->createTeam('t2');
        $this->createPendingSprint('Pending sprint', $projectOne, 't2');

        $this->createTeam('t3');
        $this->createStartedSprint('Started sprint', $projectOne, 't3');

        $this->createTeam('t4');
        $this->createClosedSprint('Closed sprint', $projectOne, 't4');

        $projectTwo = $this->createProject('p2');
        $this->createTeam('t5');
        $this->createClosedSprint('Sprint closed', $projectTwo, 't5');
        $this->createStartedSprint('Sprint started', $projectTwo, 't5');
        $this->createPendingSprint('Sprint pending', $projectTwo, 't5');
    }
}
