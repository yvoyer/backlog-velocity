<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Tests\Entity;

use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Plugin\Null\Entity\NullSprint;
use tests\UnitTestCase;

/**
 * Class NullSprintTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Tests\Entity
 *
 * @covers Star\Plugin\Null\Entity\NullSprint
 */
class NullSprintTest extends UnitTestCase
{
    /**
     * @var NullSprint
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = new NullSprint();
    }

    /**
     * @dataProvider provideDoNothingMethods
     */
    public function testShouldDoNothing($expected, $method)
    {
        $this->assertSame($expected, $this->sprint->{$method}());
    }

    public function provideDoNothingMethods()
    {
        return array(
            array(null, 'getId'),
            array(array(), 'toArray'),
            array(0, 'getActualVelocity'),
            array(0, 'getManDays'),
            array(false, 'isValid'),
            array(false, 'isClosed'),
            array(false, 'isStarted'),
            array('', 'getName'),
            array(0, 'getFocusFactor'),
        );
    }

    public function testStart()
    {
        $this->sprint->start(234, new \DateTime());
    }

    public function testClose()
    {
        $this->sprint->close(1, new \DateTime());
    }
}
