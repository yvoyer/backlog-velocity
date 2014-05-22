<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Calculator;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
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
 * @uses Star\Component\Sprint\Collection\SprintCollection
 */
class ResourceCalculatorTest extends UnitTestCase
{
    /**
     * @var ResourceCalculator
     */
    private $calculator;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->calculator = new ResourceCalculator();
    }

    /**
     * @dataProvider provideAvailableManDaysData
     *
     * @param integer $expectedVelocity
     * @param integer $availableManDays
     * @param array   $sprints
     */
    public function test_should_calculate_the_velocity($expectedVelocity, $availableManDays, array $sprints)
    {
        $closedSprints = new SprintCollection($sprints);

        $this->assertSame($expectedVelocity, $this->calculator->calculateEstimatedVelocity($availableManDays, $closedSprints));
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

    public function test_should_be_a_calculator()
    {
        $this->assertInstanceOfCalculator($this->calculator);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least 1 available man day.
     */
    public function test_should_have_available_man_days_to_start_sprint()
    {
        $this->calculator->calculateEstimatedVelocity(0, new SprintCollection());
    }
}
