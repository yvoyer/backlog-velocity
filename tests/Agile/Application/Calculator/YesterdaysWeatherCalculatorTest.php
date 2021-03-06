<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class YesterdaysWeatherCalculatorTest extends TestCase
{
    /**
     * @var YesterdaysWeatherCalculator
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new YesterdaysWeatherCalculator();
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage not implemented yet.
     */
    public function test_should_not_be_supported_yet()
    {
        $this->calculator->calculateEstimatedVelocity(SprintId::fromString('id'));
    }
}
