<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\Sprint
 */
class SprintTest extends UnitTestCase
{
    /**
     * @param string $name
     * @param int    $manDays
     * @param int    $estimatedVelocity
     * @param int    $actualVelocity
     * @param Team   $team
     *
     * @return Sprint
     */
    private function getSprint(
        $name = 'Sprint',
        $manDays = 99,
        $estimatedVelocity = 88,
        $actualVelocity = 77,
        Team $team = null
    ) {
        $team = $this->getMockTeam($team);

        return new Sprint($name, $team, $manDays, $estimatedVelocity, $actualVelocity);
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
        $this->assertInstanceOf('Star\Component\Sprint\Entity\SprintInterface', $this->getSprint());
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
}
