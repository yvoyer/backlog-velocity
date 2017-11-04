<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Calculator;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ResourceCalculatorTest extends TestCase
{
    /**
     * @var ResourceCalculator
     */
    private $calculator;

    /**
     * @var Team
     */
    private $team;

    public function setUp()
    {
        $this->team = TeamModel::fromString('id', 'name');
        $this->calculator = new ResourceCalculator();
    }

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

        $this->assertSame(
            $expectedVelocity,
            $this->calculator->calculateEstimatedVelocity(
                ProjectId::fromString('id'),
                ManDays::fromInt($availableManDays),
                $closedSprints
            )
        );
    }

    public function provideAvailableManDaysData()
    {
        $id = ProjectId::fromString('id');

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
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least 1 available man day.
     */
    public function test_should_have_available_man_days_to_start_sprint()
    {
        $this->calculator->calculateEstimatedVelocity(
            ProjectId::fromString('id'), ManDays::fromInt(0), new SprintCollection()
        );
    }
}
