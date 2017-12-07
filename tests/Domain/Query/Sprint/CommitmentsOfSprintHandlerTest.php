<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Port\CommitmentDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class CommitmentsOfSprintHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_should_return_the_sprint_details_of_not_started_sprint()
    {
        /**
         * @var CommitmentDTO[] $result
         */
        $result = $this->handle(
            new CommitmentsOfSprintHandler($this->connection),
            new CommitmentsOfSprint(SprintId::fromString('pending'))
        );

        $this->assertCount(0, $result);
    }

    public function test_it_should_return_the_sprint_details_of_started_sprint()
    {
        /**
         * @var CommitmentDTO[] $result
         */
        $result = $this->handle(
            new CommitmentsOfSprintHandler($this->connection),
            new CommitmentsOfSprint(SprintId::fromString('started'))
        );

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(CommitmentDTO::class, $result);

        $this->assertSame(12, $result[0]->manDays()->toInt());
        $this->assertSame('m1', $result[0]->memberId()->toString());
        $this->assertSame(34, $result[1]->manDays()->toInt());
        $this->assertSame('m2', $result[1]->memberId()->toString());
        $this->assertSame(56, $result[2]->manDays()->toInt());
        $this->assertSame('m3', $result[2]->memberId()->toString());
    }

    public function test_it_should_return_the_sprint_details_of_closed_sprint()
    {
        /**
         * @var CommitmentDTO[] $result
         */
        $result = $this->handle(
            new CommitmentsOfSprintHandler($this->connection),
            new CommitmentsOfSprint(SprintId::fromString('closed'))
        );

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(CommitmentDTO::class, $result);

        $this->assertSame(78, $result[0]->manDays()->toInt());
        $this->assertSame('m1', $result[0]->memberId()->toString());
        $this->assertSame(90, $result[1]->manDays()->toInt());
        $this->assertSame('m2', $result[1]->memberId()->toString());
    }

    protected function doFixtures()
    {
        $project = $this->createProject('p1');
        $this->createPendingSprint('pending', $project);
        $this->createStartedSprint('started', $project);
        $this->createClosedSprint('closed', $project);
    }
}
