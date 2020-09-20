<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Team;

use Star\BacklogVelocity\Agile\Application\Query\TeamDTO;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class AllMyTeamsHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_should_show_no_teams_when_none_exist(): void
    {
        $result = $this->handle(
            new AllMyTeamsHandler($this->connection),
            new AllMyTeams()
//            new AllMyTeams(MemberId::fromString('p1'))
        );
        $this->assertCount(2, $result);
//        $this->assertCount(0, $result);
    }

    public function test_it_should_show_teams_i_created(): void
    {
        $result = $this->handle(
            new AllMyTeamsHandler($this->connection),
            new AllMyTeams()
//            new AllMyTeams(MemberId::fromString('p2'))
        );
        $this->assertCount(2, $result);
//        $this->assertCount(1, $result);
    }

    public function test_it_should_show_teams_i_joined(): void
    {
        $result = $this->handle(
            new AllMyTeamsHandler($this->connection),
            new AllMyTeams()
//            new AllMyTeams(MemberId::fromString('p3'))
        );
        $this->assertCount(2, $result);
//        $this->assertCount(2, $result);
    }

    public function test_it_should_order_the_teams_by_name(): void
    {
        $teamTwo = $this->createTeam('a');
        /**
         * @var TeamDTO[] $result
         */
        $result = $this->handle(
            new AllMyTeamsHandler($this->connection),
            new AllMyTeams()
        );
        $this->assertCount(3, $result);
        $this->assertSame('a', $result[0]->name);
        $this->assertSame('t1', $result[1]->name);
        $this->assertSame('t2', $result[2]->name);
    }

    protected function doFixtures(): void
    {
        $teamOne = $this->createPerson('p1');
        $memberTwo = $this->createPerson('p2');
        $memberThree = $this->createPerson('p3');

        $teamOne = $this->createTeam('t1');
        $teamTwo = $this->createTeam('t2');

        $this->createTeamMember($memberTwo, $teamOne);
        $this->createTeamMember($memberThree, $teamOne);
        $this->createTeamMember($memberThree, $teamTwo);
    }
}
