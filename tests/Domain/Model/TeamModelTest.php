<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TeamModel
     */
    private $team;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    public function setUp()
    {
        $this->person = PersonModel::fromString('id', 'person-name');
        $this->team = new TeamModel(TeamId::fromString('id'), new TeamName('name'));
    }

    public function test_should_return_the_id()
    {
        $this->assertSame('id', $this->team->getId()->toString());
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->team->getName());
    }

    public function test_should_add_member()
    {
        $this->assertCount(0, $this->team->getTeamMembers());
        $this->team->addTeamMember($this->person);
        $this->assertCount(1, $this->team->getTeamMembers());

        $this->assertContainsOnly(TeamMemberDTO::class, $this->team->getTeamMembers());
    }

    /**
     * @ticket #46
     *
     * @expectedException        \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     * @expectedExceptionMessage Person 'person-name' is already part of team.
     */
    public function test_should_not_add_an_already_added_member()
    {
        $this->team->addTeamMember($this->person);
        $this->team->addTeamMember($this->person);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new TeamModel(TeamId::fromString('id'), new TeamName(''));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The person name must be string.
     */
    public function test_should_throw_exception_when_name_not_string()
    {
        $this->team->addTeamMember($this->getMockBuilder(Person::class)->getMock());
    }

    public function test_it_should_return_the_team_members()
    {
        $this->team->addTeamMember(PersonModel::fromString('id-1', 'name-1'));
        $this->team->addTeamMember(PersonModel::fromString('id-2', 'name-2'));
        $this->team->addTeamMember(PersonModel::fromString('id-3', 'name-3'));
        $this->assertCount(3, $this->team->getTeamMembers());
        $this->assertContainsOnlyInstancesOf(TeamMemberDTO::class, $members = $this->team->getTeamMembers());
        $this->assertEquals(PersonId::fromString('id-1'), $members[0]->personId());
        $this->assertSame('name-1', $members[0]->name());
        $this->assertEquals(PersonId::fromString('id-2'), $members[1]->personId());
        $this->assertSame('name-2', $members[1]->name());
        $this->assertEquals(PersonId::fromString('id-3'), $members[2]->personId());
        $this->assertSame('name-3', $members[2]->name());
    }
}
