<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Calculator;

use Star\Component\Sprint\Calculator\EstimatedFocusCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use tests\Stub\Sprint\StubSprint;
use tests\UnitTestCase;

/**
 * Class EstimatedFocusCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
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
        $sprintCollection = $sprints;

        $this->assertSame($expectedFocus, $calculator->calculateEstimatedFocus($sprintCollection));
    }

    public function providePastFocusFactorData()
    {
        return array(
            array(0, array()),
            array(50, array(StubSprint::withFocus(50))),
            array(65, array(StubSprint::withFocus(50), StubSprint::withFocus(80))),
            array(67, array(StubSprint::withFocus(50), StubSprint::withFocus(80), StubSprint::withFocus(70))),
        );
    }
}
