<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprinterDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\SprinterData
 */
class SprinterDataTest extends UnitTestCase
{
    /**
     * @param string $name
     *
     * @return SprinterData
     */
    public function getSprinter($name = '')
    {
        return new SprinterData($name);
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->getSprinter());
    }

    public function testShouldBeSprinter()
    {
        $this->assertInstanceOfSprinter($this->getSprinter());
    }

    public function testShouldHaveAName()
    {
        $name = uniqid('name');
        $this->assertSame($name, $this->getSprinter($name)->getName());
    }
}
