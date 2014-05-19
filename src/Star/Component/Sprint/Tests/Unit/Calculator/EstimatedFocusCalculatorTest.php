<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Calculator;

use Star\Component\Sprint\Calculator\EstimatedFocusCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class EstimatedFocusCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Calculator
 *
 * @covers Star\Component\Sprint\Calculator\EstimatedFocusCalculator
 * @covers Star\Component\Sprint\Collection\SprintCollection
 * @deprecated
 */
class EstimatedFocusCalculatorTest extends UnitTestCase
{
    /**
     * @dataProvider providePastFocusFactorData
     *
     * @param integer $expectedFocus
     * @param array   $sprints
     */
    public function testShouldCalculateTheEstimatedFocus($expectedFocus, array $sprints)
    {
        $calculator = new EstimatedFocusCalculator();
        $sprintCollection = new SprintCollection($sprints);

        $this->assertSame($expectedFocus, $calculator->calculateEstimatedFocus($sprintCollection));
    }

    public function providePastFocusFactorData()
    {
        return array(
            array(0, array()),
            array(50, array(new Sprint1())),
            array(65, array(new Sprint1(), new Sprint2())),
            array(67, array(new Sprint1(), new Sprint2(), new Sprint3())),
        );
    }
}
