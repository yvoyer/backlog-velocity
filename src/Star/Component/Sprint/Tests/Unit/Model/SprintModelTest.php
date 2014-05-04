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
        $this->markTestSkipped('come back');
        $this->assertSame(0, $this->sprint->getActualVelocity());
        $this->sprint->close(40);
        $this->assertSame(40, $this->sprint->getActualVelocity());
    }
}
 