<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprintTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\Sprint
 */
class SprintTest extends \PHPUnit_Framework_TestCase
{
    private function getSprint($name = 'Sprint', $manDays = 99, $estimatedVelocity = 88, $actualVelocity = 77)
    {
        return new Sprint($name, $manDays, $estimatedVelocity, $actualVelocity);
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
        $this->assertInstanceOf('Star\Component\Sprint\Entity\EntityInterface', $this->getSprint());
    }

    public function testShouldReturnTheArrayRepresentation()
    {
        $expected = array(
            'id'   => 'name',
            'name' => 'name',
        );

        $this->assertSame($expected, $this->getSprint('name')->toArray());
    }
}
