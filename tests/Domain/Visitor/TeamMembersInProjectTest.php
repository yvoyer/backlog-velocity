<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Visitor;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Plugin\Null\Entity\NullPerson;
use Star\Plugin\Null\Entity\NullProject;
use Star\Plugin\Null\Entity\NullTeam;

final class TeamMembersInProjectTest extends TestCase
{
    /**
     * @var TeamMembersInProject
     */
    private $visitor;

    public function setUp()
    {
        $this->visitor = new TeamMembersInProject();
    }

    public function test_it_should_return_the_persons_from_the_project()
    {
        $this->visitor->visitProject(new NullProject());
        $this->visitor->visitTeam(new NullTeam());
        $this->visitor->visitTeamMember(new NullPerson());
        $this->visitor->visitTeamMember(new NullPerson());
        $this->assertCount(2, $this->visitor->getMembers());
        $this->assertContainsOnlyInstancesOf(PersonId::class, $this->visitor->getMembers());
    }

    public function test_it_should_not_return_duplicate_persons_that_are_in_multiple_teams()
    {
        $this->visitor->visitProject(new NullProject());
        $this->visitor->visitTeam(new NullTeam());
        $this->visitor->visitTeamMember($pOne = new NullPerson());
        $this->visitor->visitTeamMember(new NullPerson());
        $this->visitor->visitTeam(new NullTeam());
        $this->visitor->visitTeamMember($pOne);

        $this->assertCount(2, $this->visitor->getMembers());
        $this->assertContainsOnlyInstancesOf(PersonId::class, $this->visitor->getMembers());
    }

    public function test_it_should_restart_list_when_visiting_project()
    {
        $this->visitor->visitProject(new NullProject());
        $this->visitor->visitTeam(new NullTeam());
        $this->visitor->visitTeamMember(new NullPerson());
        $this->visitor->visitTeamMember(new NullPerson());
        $this->assertCount(2, $this->visitor->getMembers());

        $this->visitor->visitProject(new NullProject());

        $this->assertCount(0, $this->visitor->getMembers());
    }
}
