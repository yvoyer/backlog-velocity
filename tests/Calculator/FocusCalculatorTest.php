<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Calculator;

use Star\Component\Sprint\Calculator\FocusCalculator;
use tests\UnitTestCase;

/**
 * Class FocusCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Calculator
 *
 * @covers Star\Component\Sprint\Calculator\FocusCalculator
 */
class FocusCalculatorTest extends UnitTestCase
{
    /**
     * @dataProvider getFocusCalculatorData
     *
     * @param $expected
     * @param $velocity
     * @param $manDays
     */
    public function testShouldCalculateTheFocus($expected, $velocity, $manDays)
    {
        $calculator = new FocusCalculator();
        $this->assertSame($expected, $calculator->calculate($manDays, $velocity));
    }

    public function getFocusCalculatorData()
    {
        return array(
            array(50, 30, 60),
            array(41, 50, 120),
            array(21, 17, 80),
            array(193, 58, 30),
            array(101, 56, 55),
            'Should not divide by 0' => array(0, 60, 0),
        );
    }
}
