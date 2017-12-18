<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Calculator;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Calculator\YesterdaysWeatherCalculator;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ManDays;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class YesterdaysWeatherCalculatorTest extends TestCase
{
    /**
     * @var YesterdaysWeatherCalculator
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new YesterdaysWeatherCalculator();
    }

    public function test_should_be_a_velocity_calculator()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Domain\Calculator\VelocityCalculator', $this->calculator);
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage not implemented yet.
     */
    public function test_should_not_be_supported_yet()
    {
        $sprintCollection = $this->getMockBuilder('Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $this->calculator->calculateEstimatedVelocity(
            ProjectId::fromString('id'), ManDays::fromInt(123), $sprintCollection
        );
    }
}
