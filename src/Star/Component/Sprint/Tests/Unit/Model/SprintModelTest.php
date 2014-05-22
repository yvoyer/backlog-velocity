<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 *
 * @covers Star\Component\Sprint\Model\SprintModel
 */
class SprintModelTest extends UnitTestCase
{
    /**
     * @var SprintModel
     */
    private $sprint;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->sprint = new SprintModel('name', $this->team);
    }

    public function testShouldBeASprint()
    {
        $this->assertInstanceOfSprint($this->sprint);
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('name', $this->sprint->getName());
    }

    public function testShouldReturnTheTeam()
    {
        $this->assertSame($this->team, $this->sprint->getTeam());
    }

    public function testShouldReturnTheActualVelocity()
    {
        $this->assertSame(0, $this->sprint->getActualVelocity());
        $this->sprint->close(40);
        $this->assertSame(40, $this->sprint->getActualVelocity());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function testShouldHaveAValidName()
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
        $this->sprint->close(34);
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started');
        $this->assertTrue($this->sprint->isClosed(), 'The sprint should be closed');
    }
}
 