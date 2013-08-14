<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Calculator;

use Star\Component\Sprint\Calculator\AverageCalculator;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class AverageCalculatorTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Calculator
 *
 * @covers Star\Component\Sprint\Calculator\AverageCalculator
 */
class AverageCalculatorTest extends UnitTestCase
{
    /**
     * @dataProvider provideAverageNumbers
     *
     * @param $expectedAvg
     * @param array $data
     */
    public function testShouldCalculateTheAverage($expectedAvg, array $data)
    {
        $calculator = new AverageCalculator($data);
        $this->assertSame($expectedAvg, $calculator->calculate());
    }

    public function provideAverageNumbers()
    {
        return array(
            array(3, array(3)),
            array(6, array(2, 4, 6, 8, 10)),
            array(0.5, array(0, 1)),
        );
    }
}
