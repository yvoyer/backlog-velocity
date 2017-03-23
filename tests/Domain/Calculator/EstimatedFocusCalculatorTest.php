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
        $sprintCollection = new SprintCollection($sprints);

        $this->assertSame($expectedFocus, $calculator->calculateEstimatedFocus($sprintCollection));
    }

    public function providePastFocusFactorData()
    {
        return array(
            array(0, array()),
            array(50, array(new StubSprint(50))),
            array(65, array(new StubSprint(50), new StubSprint(80))),
            array(67, array(new StubSprint(50), new StubSprint(80), new StubSprint(70))),
        );
    }
}
