<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Model\SprintModel;
use tests\UnitTestCase;

/**
 * Class SprintModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 *
 * @covers Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Entity\Id\SprintId
 * @uses Star\Component\Sprint\Type\String
 */
class SprintModelTest extends UnitTestCase
{
    /**
     * @var SprintModel
     */
    private $sprint;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMember;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $calculator;

    public function setUp()
    {
        $this->teamMember = $this->getMockTeamMember();
        $this->person = $this->getMockPerson();
        $this->calculator = $this->getMockFocusCalculator();
        $this->team = $this->getMockTeam();
        $this->sprint = new SprintModel('name', $this->team);
    }

    public function test_should_be_a_sprint()
    {
        $this->assertInstanceOfSprint($this->sprint);
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->sprint->getName());
    }

    public function test_should_return_the_team()
    {
        $this->assertSame($this->team, $this->sprint->getTeam());
    }

    public function test_should_return_the_actual_velocity()
    {
        $this->assertSame(0, $this->sprint->getActualVelocity());
        $this->sprint->close(40, $this->calculator);
        $this->assertSame(40, $this->sprint->getActualVelocity());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new SprintModel('', $this->team);
    }

    public function test_should_define_estimated_velocity()
    {
        $this->assertSame(0, $this->sprint->getEstimatedVelocity());
        $this->sprint->start(46);
        $this->assertSame(46, $this->sprint->getEstimatedVelocity());
    }

    public function test_starting_sprint_should_start_it()
    {
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started by default');
        $this->sprint->start(46);
        $this->assertTrue($this->sprint->isStarted(), 'The sprint should be started');
    }

    /**
     * @depends test_starting_sprint_should_start_it
     */
    public function test_closing_sprint_should_close_it()
    {
        $this->sprint->start(46);
        $this->assertFalse($this->sprint->isClosed(), 'The sprint should not be closed');
        $this->sprint->close(34, $this->calculator);
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started');
        $this->assertTrue($this->sprint->isClosed(), 'The sprint should be closed');
    }

    public function test_should_have_a_focus_factor()
    {
        $this->calculator
            ->expects($this->once())
            ->method('calculate')
            ->will($this->returnValue(50));

        $this->assertSame(0, $this->sprint->getFocusFactor());
        $this->sprint->start(32);
        $this->sprint->close(16, $this->calculator);
        $this->assertSame(50, $this->sprint->getFocusFactor());
    }

    public function test_should_return_the_id()
    {
        $this->assertSame('name', (string) $this->sprint->getId());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockFocusCalculator()
    {
        return $this->getMock('Star\Component\Sprint\Calculator\FocusCalculator', array(), array(), '', false);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint member 'person-name' is already added.
     */
    public function test_should_throw_exception_when_sprint_member_already_added()
    {
        $this->teamMember
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('person-name'));

        $this->sprint->commit($this->teamMember, 43);
        $this->sprint->commit($this->teamMember, 43);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint is already started.
     */
    public function test_should_throw_exception_when_sprint_is_already_started()
    {
        $this->sprint->start(345);
        $this->sprint->start(39);
    }

    public function test_should_add_sprint_member_to_sprint()
    {
        $this->assertCount(0, $this->sprint->getSprintMembers());
        $this->sprint->commit($this->teamMember, 12);
        $this->assertCount(1, $this->sprint->getSprintMembers());
    }
}
 