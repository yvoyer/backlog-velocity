<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Type;

use Star\Component\Sprint\Type\Integer;

/**
 * Class IntegerIdTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Type
 *
 * @covers Star\Component\Sprint\Type\Integer
 */
class IntegerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Integer
     */
    private $id;

    public function setUp()
    {
        $this->id = new Integer(2);
    }

    public function testShouldReturnTheStringRepresentation()
    {
        $this->assertSame('2', (string) $this->id);
    }

    /**
     * @param $value
     *
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The value must be numeric.
     *
     * @dataProvider provideInvalidValues
     */
    public function testShouldThrowExceptionWithInvalidValue($value)
    {
        new Integer($value);
    }

    public function provideInvalidValues()
    {
        return array(
            array(''),
            array('12.34'),
            array(12.34),
            array(false),
            array(true),
            array(null),
            array(array()),
            array(new \stdClass()),
        );
    }
}
 