<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Builder\SprintBuilder;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;
use Star\BacklogVelocity\Agile\Domain\Stub\StubSprint;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;

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

        $actual = $calculator->calculateEstimatedVelocity(SprintId::fromString('sid'));
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
            'Should round focus factor' => array(
                50, 50, array(StubSprint::withFocus(83, $id), StubSprint::withFocus(88, $id), StubSprint::withFocus(128, $id))
            ),
        );
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least 1 available man day.
     */
    public function test_should_have_available_man_days_to_start_sprint()
    {
        $sprint = SprintBuilder::pending('id', 'pid', 'tid')->buildSprint();
        $calculator = new ResourceCalculator(new SprintCollection([$sprint]));
        $calculator->calculateEstimatedVelocity($sprint->getId());
    }
}