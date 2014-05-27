<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Calculator;

use Star\Component\Sprint\Calculator\EstimatedFocusCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use tests\Stub\Sprint\Sprint1;
use tests\Stub\Sprint\Sprint2;
use tests\Stub\Sprint\Sprint3;
use tests\UnitTestCase;

/**
 * Class EstimatedFocusCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Calculator
 *
 * @covers Star\Component\Sprint\Calculator\EstimatedFocusCalculator
 * @uses Star\Component\Sprint\Collection\SprintCollection
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
