<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

/**
 * @group functional
 */
final class DoctrineSprintRepositoryTest extends DbalQueryHandlerTest
{
    public function test_it_should_return_default_focus_when_no_sprint_exists()
    {
        $this->assertSprintCount(0);
        $result = $this->sprints->estimatedFocusOfPastSprints(TeamId::uuid(), new \DateTimeImmutable());
        $this->assertCount(0, $result);
    }

    public function test_it_should_calculate_between_sprints_of_team()
    {
        $project = $this->createProject('p1');
        $this->createTeam('t1');
        $this->createTeam('t2');

        $sprintOne = $this->createPendingSprint('s1', $project, 't1');
        $this->closeSprintWithId($sprintOne, 10, 8); // 80%
        $this->assertSame(80, $sprintOne->getFocusFactor()->toInt());
        $sprintTwo = $this->createPendingSprint('s2', $project, 't1');
        $this->closeSprintWithId($sprintTwo, 10, 7); // 70%
        $this->assertSame(70, $sprintTwo->getFocusFactor()->toInt());
        $sprintThree = $this->createPendingSprint('s3', $project, 't2');
        $this->closeSprintWithId($sprintThree, 10, 8); // ignored
        $this->assertSame(80, $sprintThree->getFocusFactor()->toInt());
        $this->assertSprintCount(3);

        $result = $this->sprints->estimatedFocusOfPastSprints(
            TeamId::fromString('t1'), new \DateTime('now +1 day')
        );
        $this->assertContainsOnlyInstancesOf(FocusFactor::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame(80, $result[0]->toInt());
        $this->assertSame(70, $result[1]->toInt());
    }

    public function test_it_should_calculate_between_sprints_with_closed_date_lower_or_equals_to_date()
    {
        $project = $this->createProject('p1');
        $this->createTeam('t1');
        $sprintOne = $this->createPendingSprint('s1', $project, 't1');
        $this->closeSprintWithId($sprintOne, 10, 9, new \DateTime('2000-01-01')); // 90%
        $this->assertSame(90, $sprintOne->getFocusFactor()->toInt());
        $sprintTwo = $this->createPendingSprint('s2', $project, 't1');
        $this->closeSprintWithId($sprintTwo, 10, 6, new \DateTime()); // ignore because of date
        $this->assertSame(60, $sprintTwo->getFocusFactor()->toInt());
        $sprintThree = $this->createPendingSprint('s3', $project, 't1');
        $this->closeSprintWithId($sprintThree, 10, 7, new \DateTime('1999-01-01')); // 70%
        $this->assertSame(70, $sprintThree->getFocusFactor()->toInt());
        $this->assertSprintCount(3);

        $result = $this->sprints->estimatedFocusOfPastSprints(
            TeamId::fromString('t1'), new \DateTimeImmutable('2000-01-01')
        );

        $this->assertContainsOnlyInstancesOf(FocusFactor::class, $result);
        $this->assertCount(2, $result);
        $this->assertSame(90, $result[0]->toInt());
        $this->assertSame(70, $result[1]->toInt());
    }

    public function test_it_should_calculate_with_closed_sprint_only()
    {
        $project = $this->createProject('p1');
        $this->createTeam('t1');
        $sprintOne = $this->createPendingSprint('s1', $project, 't1');
        $this->createPendingSprint('s2', $project, 't1');
        $this->createStartedSprint('s3', $project, 't1');
        $this->closeSprintWithId($sprintOne, 10, 8); // 80%
        $this->assertSame(80, $sprintOne->getFocusFactor()->toInt());
        $this->assertSprintCount(3);

        $result = $this->sprints->estimatedFocusOfPastSprints(
            TeamId::fromString('t1'), new \DateTimeImmutable()
        );

        $this->assertContainsOnlyInstancesOf(FocusFactor::class, $result);
        $this->assertCount(1, $result);
        $this->assertSame(80, $result[0]->toInt());
    }

    protected function doFixtures()
    {
    }
}
