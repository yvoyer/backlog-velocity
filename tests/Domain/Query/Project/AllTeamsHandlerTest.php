<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Project;

use Star\Component\Sprint\Domain\Port\TeamDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class AllTeamsHandlerTest extends DbalQueryHandlerTest
{
    public function test_it_return_all_teams()
    {
        $this->createTeam('t1');
        $this->createTeam('t2');
        $this->createTeam('t3');

        $result = $this->handle(
            new AllTeamsHandler($this->connection),
            new AllTeams()
        );

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(TeamDTO::class, $result);
    }

    public function test_it_return_no_teams()
    {
        $result = $this->handle(
            new AllTeamsHandler($this->connection),
            new AllTeams()
        );

        $this->assertCount(0, $result);
    }

    /**
     * @depends test_it_return_all_teams
     */
    public function test_it_should_order_by_name()
    {
        $this->createTeam('zzz');
        $this->createTeam('yasd');
        $this->createTeam('yb');

        $result = $this->handle(
            new AllTeamsHandler($this->connection),
            new AllTeams()
        );

        $this->assertCount(3, $result);
        $this->assertSame('yasd', $result[0]->name);
        $this->assertSame('yb', $result[1]->name);
        $this->assertSame('zzz', $result[2]->name);
    }

    protected function doFixtures()
    {
    }
}
