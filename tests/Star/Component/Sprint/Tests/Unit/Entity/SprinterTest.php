<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprinterTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\Sprinter
 */
class SprinterTest extends UnitTestCase
{
    /**
     * @param string $name
     *
     * @return Sprinter
     */
    public function getSprinter($name = '')
    {
        return new Sprinter($name);
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->getSprinter());
    }

    public function testShouldHaveAName()
    {
        $name = uniqid('name');
        $this->assertSame($name, $this->getSprinter($name)->getName());
    }
}
