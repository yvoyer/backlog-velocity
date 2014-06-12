<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Model\TeamMemberModel;
use tests\UnitTestCase;

/**
 * Class TeamMemberModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 *
 * @covers Star\Component\Sprint\Model\TeamMemberModel
 */
class TeamMemberModelTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var TeamMemberModel
     */
    private $teamMember;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->person = $this->getMockPerson();

        $this->teamMember = new TeamMemberModel($this->team, $this->person);
    }

    public function test_should_return_the_id()
    {
        $this->assertNull($this->teamMember->getId());
    }

    public function test_should_return_the_person()
    {
        $this->assertSame($this->person, $this->teamMember->getPerson());
    }

    public function test_should_return_the_team()
    {
        $this->assertSame($this->team, $this->teamMember->getTeam());
    }

    public function test_should_return_whether_the_team_member_is_equals()
    {
        $this->assertTrue($this->teamMember->isEqual($this->teamMember));
        $this->assertFalse($this->teamMember->isEqual($this->getMockTeamMember()));
    }

    public function test_should_return_whether_the_person_is_equals()
    {
        $this->assertTrue($this->teamMember->isEqual($this->person));
        $this->assertFalse($this->teamMember->isEqual($this->getMockPerson()));
    }

    public function test_should_return_the_name()
    {
        $this->person
            ->expects($this->once())
            ->method('getName')
            ->willReturn('name');

        $this->assertSame('name', $this->teamMember->getName());
    }

    public function test_should_change_the_available_man_days()
    {
        $this->assertSame(0, $this->teamMember->getAvailableManDays());
        $this->teamMember->setAvailableManDays(5);
        $this->assertSame(5, $this->teamMember->getAvailableManDays());
    }
}
 