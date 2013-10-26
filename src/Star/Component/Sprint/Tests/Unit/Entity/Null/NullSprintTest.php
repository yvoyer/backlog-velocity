<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Null;

use Star\Component\Sprint\Entity\Null\NullSprint;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullSprintTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Null
 *
 * @covers Star\Component\Sprint\Entity\Null\NullSprint
 */
class NullSprintTest extends UnitTestCase
{
    /**
     * @var NullSprint
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new NullSprint();
    }

    /**
     * @dataProvider provideDoNothingMethods
     */
    public function testShouldDoNothing($expected, $method)
    {
        $this->assertSame($expected, $this->sut->{$method}());
    }

    public function provideDoNothingMethods()
    {
        return array(
            array(null, 'getId'),
            array(array(), 'toArray'),
            array(0, 'getActualVelocity'),
            array(0, 'getManDays'),
            array(false, 'isValid'),
        );
    }

    public function testSetName()
    {
        $this->sut->setName('name');
        $this->assertSame('', $this->sut->getName());
    }
}
