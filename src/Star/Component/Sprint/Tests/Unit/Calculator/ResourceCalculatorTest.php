<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Calculator;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class ResourceCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Calculator
 *
 * @covers Star\Component\Sprint\Calculator\ResourceCalculator
 */
class ResourceCalculatorTest extends UnitTestCase
{
    /**
     * @var ResourceCalculator
     */
    private $sut;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->sut = new ResourceCalculator();
    }

    /**
     * @dataProvider provideAvailableManDaysData
     *
     * @param integer $expectedVelocity
     * @param integer $availableManDays
     * @param array   $sprints
     */
    public function testShouldCalculateTheVelocity($expectedVelocity, $availableManDays, array $sprints)
    {
        $this->team
            ->expects($this->once())
            ->method('getClosedSprints')
            ->will($this->returnValue($sprints));

        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('getAvailableManDays')
            ->will($this->returnValue($availableManDays));
        $this->assertGetTeamIsCalled($sprint);

        $this->assertSame($expectedVelocity, $this->sut->calculateEstimatedVelocity($sprint));
    }

    public function provideAvailableManDaysData()
    {
        return array(
            'Should calculate using base focus when no stat available' => array(
                35, 50, array(),
            ),
            'Should calculate the second sprint using the first sprint actual velocity' => array(
                25, 50, array(new Sprint1())
            ),
            'Should calculate the third sprint using the past two sprints actual velocities' => array(
                32, 50, array(new Sprint1(), new Sprint2())
            ),
            'Should calculate the fourth sprint using the past three sprints actual velocities' => array(
                33, 50, array(new Sprint1(), new Sprint2(), new Sprint3())
            ),
        );
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The calculator expects only sprints.
     */
    public function testShouldThrowExceptionIfSprintCollectionDoNotRespectInterface()
    {
        $sprint = $this->getMockSprint();

        $this->team
            ->expects($this->once())
            ->method('getClosedSprints')
            ->will($this->returnValue(array('')));
        $this->assertGetTeamIsCalled($sprint);

        $this->sut->calculateEstimatedVelocity($sprint);
    }

    /**
     * @param $sprint
     */
    private function assertGetTeamIsCalled($sprint)
    {
        $sprint
            ->expects($this->once())
            ->method('getTeam')
            ->will($this->returnValue($this->team));
    }
}
