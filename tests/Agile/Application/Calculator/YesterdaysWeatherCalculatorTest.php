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

	protected function setUp(): void
    {
        $this->calculator = new YesterdaysWeatherCalculator();
    }

    public function test_should_not_be_supported_yet(): void
    {
    	$this->expectException(\RuntimeException::class);
    	$this->expectExceptionMessage('not implemented yet.');
        $this->calculator->calculateEstimatedVelocity(SprintId::fromString('id'));
    }
}
