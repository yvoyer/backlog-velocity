<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Project;

use Star\BacklogVelocity\Agile\Application\Query\TeamMemberDTO;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

/**
 * @group functional
 */
final class AllMembersOfTeamHandlerTest extends DbalQueryHandlerTest
{
    protected function doFixtures()
    {
        $member1 = $this->createPerson('m1');
        $member2 = $this->createPerson('m2');
        $member3 = $this->createPerson('m3');
        $member4 = $this->createPerson('m4');
        $member5 = $this->createPerson('m5');
        $member6 = $this->createPerson('m6');

        $this->createTeam('t1');

        $team2 = $this->createTeam('t2');
        $this->createTeamMember($member1, $team2);
        $this->createTeamMember($member2, $team2);
        $this->createTeamMember($member3, $team2);
        $this->createTeamMember($member4, $team2);
        $this->createTeamMember($member5, $team2);
        $this->createTeamMember($member6, $team2);

        $team3 = $this->createTeam('t3');
        $team4 = $this->createTeam('t4');
        $this->createTeamMember($member1, $team3);
        $this->createTeamMember($member2, $team3);
        $this->createTeamMember($member1, $team4);
        $this->createTeamMember($member2, $team4);
    }

    public function test_it_should_return_no_members_when_no_members_in_team()
    {
        $result = $this->handle(
            new AllMembersOfTeamHandler($this->connection),
            new AllMembersOfTeam(TeamId::fromString('t1'))
        );

        $this->assertCount(0, $result);
    }

    public function test_it_should_return_all_members_of_the_project_teams()
    {
        $result = $this->handle(
            new AllMembersOfTeamHandler($this->connection),
            new AllMembersOfTeam(TeamId::fromString('t2'))
        );

        $this->assertCount(6, $result);
        $this->assertContainsOnlyInstancesOf(TeamMemberDTO::class, $result);
    }

    public function test_it_should_return_unique_members_when_many_persons_may_work_on_different_teams()
    {
        $result = $this->handle(
            new AllMembersOfTeamHandler($this->connection),
            new AllMembersOfTeam(TeamId::fromString('t3'))
        );

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(TeamMemberDTO::class, $result);
    }
}
