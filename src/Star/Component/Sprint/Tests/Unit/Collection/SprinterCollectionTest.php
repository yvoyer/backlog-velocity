<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Collection;

use Star\Component\Sprint\Collection\SprinterCollection;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprinterCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Collection
 * todo rename to SprintMemberCollectionTest
 * @covers Star\Component\Sprint\Collection\SprinterCollection
 */
class SprinterCollectionTest extends UnitTestCase
{
    /**
     * @var SprinterCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new SprinterCollection();
    }

    public function testShouldContainSprinters()
    {
        $this->assertCount(0, $this->collection);
        $this->collection->addSprinter($this->getMockSprinter());
        $this->assertCount(1, $this->collection);
        $this->collection->addSprinter($this->getMockSprinter());
        $this->assertCount(2, $this->collection);
    }

    public function testShouldBeIterable()
    {
        $this->collection->addSprinter($this->getMockSprinter());
        foreach ($this->collection as $element) {
            $this->assertInstanceOfSprinter($element);
        }
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $sprinter = $this->getMockSprinter();
        $sprinter
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->addSprinter($sprinter);
        $this->assertInstanceOfSprinter($this->collection->findOneByName('name'));
    }
}
 