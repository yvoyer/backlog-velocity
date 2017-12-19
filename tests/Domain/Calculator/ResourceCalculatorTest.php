<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Calculator;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Builder\SprintBuilder;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Velocity;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ResourceCalculatorTest extends TestCase
{
    /**
     * @dataProvider provideAvailableManDaysData
     *
     * @param integer $expectedVelocity
     * @param integer $availableManDays
     * @param array   $sprints
     */
    public function test_should_calculate_the_velocity($expectedVelocity, $availableManDays, array $sprints)
    {
        $closedSprints = new SprintCollection($sprints);
        $closedSprints->saveSprint(
            SprintBuilder::pending('sid', 'pid', 'tid')
                ->committedMember('mid', $availableManDays)
                ->buildSprint()
        );
        $calculator = new ResourceCalculator($closedSprints);

        $actual = $calculator->calculateEstimateOfSprint(SprintId::fromString('sid'));
        $this->assertInstanceOf(Velocity::class, $actual);
        $this->assertSame($expectedVelocity, $actual->toInt());
    }

    public function provideAvailableManDaysData()
    {
        $id = ProjectId::fromString('pid');

        return array(
            'Should calculate using base focus when no stat available' => array(
                35, 50, array(),
            ),
            'Should calculate the velocity based on the only past sprint focus factor' => array(
                25, 50, array(StubSprint::withFocus(50, $id))
            ),
            'Should calculate the velocity using the average of the past two sprints focus factors' => array(
                32, 50, array(StubSprint::withFocus(50, $id), StubSprint::withFocus(80, $id))
            ),
            'Should calculate the velocity using the past three past sprints focus factors' => array(
                33, 50, array(StubSprint::withFocus(50, $id), StubSprint::withFocus(80, $id), StubSprint::withFocus(70, $id))
            ),
        );
    }

    /**
     * @expectedException        \Star\Component\Sprint\Domain\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least 1 available man day.
     */
    public function test_should_have_available_man_days_to_start_sprint()
    {
        $sprint = SprintBuilder::pending('id', 'pid', 'tid')->buildSprint();
        $calculator = new ResourceCalculator(new SprintCollection([$sprint]));
        $calculator->calculateEstimateOfSprint($sprint->getId());
    }
}
