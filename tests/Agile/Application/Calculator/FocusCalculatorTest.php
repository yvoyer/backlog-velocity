<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class FocusCalculatorTest extends TestCase
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
        $focus = $calculator->calculate(ManDays::fromInt($manDays), Velocity::fromInt($velocity));
        $this->assertInstanceOf(FocusFactor::class, $focus);
        $this->assertSame($expected, $focus->toInt());
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
