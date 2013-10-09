<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Entity\Team;

/**
 * Class SprintTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\SprintData
 */
class SprintTest extends AbstractValueProvider
{
    /**
     * @param string $name
     * @param int    $manDays
     * @param int    $estimatedVelocity
     * @param int    $actualVelocity
     * @param Team   $team
     *
     * @return SprintData
     */
    private function getSprint(
        $name = 'Sprint',
        $manDays = 99,
        $estimatedVelocity = 88,
        $actualVelocity = 77,
        Team $team = null
    ) {
        $team = $this->getMockTeam($team);

        return new SprintData($name, $team, $manDays, $estimatedVelocity, $actualVelocity);
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('Sprint', $this->getSprint()->getName());
    }

    public function testShouldReturnTheManDays()
    {
        $this->assertSame(99, $this->getSprint()->getManDays());
    }

    public function testShouldReturnTheEstimatedVelocity()
    {
        $this->assertSame(88, $this->getSprint()->getEstimatedVelocity());
    }

    public function testShouldReturnTheActualVelocity()
    {
        $this->assertSame(77, $this->getSprint()->getActualVelocity());
    }

    public function testShouldReturnTheFocusFactor()
    {
        $this->assertSame(50, $this->getSprint(null, 60, null, 30)->getFocusFactor());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->getSprint());
    }

    public function testShouldBeSprint()
    {
        $this->assertInstanceOfSprint($this->getSprint());
    }

    public function testShouldReturnTheArrayRepresentation()
    {
        $expected = array(
            'id'   => null,
            'name' => 'name',
        );

        $this->assertSame($expected, $this->getSprint('name')->toArray());
    }

    public function testShouldReturnsTheTeam()
    {
        $team = $this->getMockTeam();
        $this->assertSame($team, $this->getSprint(null, null, null, null, $team)->getTeam());
    }

    /**
     * @dataProvider providerValidNames
     *
     * @param $name
     */
    public function testShouldBeValid($name)
    {
        $this->assertTrue($this->getSprint($name)->isValid());
    }

    /**
     * @dataProvider providerInvalidNames
     *
     * @param $name
     */
    public function testShouldNotBeValid($name)
    {
        $this->assertFalse($this->getSprint($name)->isValid());
    }
}
