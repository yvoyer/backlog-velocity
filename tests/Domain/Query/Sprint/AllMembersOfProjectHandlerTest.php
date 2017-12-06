<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;
use Star\Component\Sprint\Infrastructure\Persistence\Doctrine\DbalQueryHandlerTest;

final class AllMembersOfProjectHandlerTest extends DbalQueryHandlerTest
{
    protected function doFixtures()
    {
        $member1 = $this->createPerson('m1');
        $member2 = $this->createPerson('m2');
        $member3 = $this->createPerson('m3');
        $member4 = $this->createPerson('m4');
        $member5 = $this->createPerson('m5');
        $member6 = $this->createPerson('m6');

        $project1 = $this->createProject('p1');

        $project2 = $this->createProject('p2');
        $this->createTeam('t1', $project2);

        $project3 = $this->createProject('p3');
        $team2 = $this->createTeam('t2', $project3);
        $this->createTeamMember($member1, $team2);
        $this->createTeamMember($member2, $team2);
        $this->createTeamMember($member3, $team2);
        $this->createTeamMember($member4, $team2);
        $this->createTeamMember($member5, $team2);
        $this->createTeamMember($member6, $team2);

        $project4 = $this->createProject('p4');
        $team3 = $this->createTeam('t3', $project4);
        $team4 = $this->createTeam('t4', $project4);
        $this->createTeamMember($member1, $team3);
        $this->createTeamMember($member2, $team3);
        $this->createTeamMember($member1, $team4);
        $this->createTeamMember($member2, $team4);
    }

    public function test_it_should_return_no_members_when_no_team_in_project()
    {
        $result = $this->handle(
            new AllMembersOfProjectHandler($this->connection),
            new AllMembersOfProject(ProjectId::fromString('p1'))
        );

        $this->assertCount(0, $result);
    }

    public function test_it_should_return_no_members_when_no_members_in_team()
    {
        $result = $this->handle(
            new AllMembersOfProjectHandler($this->connection),
            new AllMembersOfProject(ProjectId::fromString('p2'))
        );

        $this->assertCount(0, $result);
    }

    public function test_it_should_return_all_members_of_the_project_teams()
    {
        $result = $this->handle(
            new AllMembersOfProjectHandler($this->connection),
            new AllMembersOfProject(ProjectId::fromString('p3'))
        );

        $this->assertCount(6, $result);
        $this->assertContainsOnlyInstancesOf(TeamMemberDTO::class, $result);
    }

    public function test_it_should_return_unique_members_when_many_persons_may_work_on_different_teams()
    {
        $result = $this->handle(
            new AllMembersOfProjectHandler($this->connection),
            new AllMembersOfProject(ProjectId::fromString('p4'))
        );

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(TeamMemberDTO::class, $result);
    }
}
