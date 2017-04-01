<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\UnitTestCase;

/**
 * Class TeamModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Collection\SprintCollection
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Model\Identity\SprintId
 * @uses Star\Component\Sprint\Model\Identity\TeamId
 * @uses Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Model\TeamMemberModel
 * @uses Star\Component\Sprint\Type\String
 */
class TeamModelTest extends UnitTestCase
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
        $this->person = $this->getMockPerson();
        $this->person
            ->expects($this->any())
            ->method('getName')
            ->willReturn('person-name');

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

        $this->assertContainsOnly(TeamMemberModel::class, $this->team->getTeamMembers());
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
        $this->team->addTeamMember($this->getMockPerson());
    }
}
