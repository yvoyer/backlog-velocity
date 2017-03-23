<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;
use tests\UnitTestCase;

/**
 * Class SprintCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\SprintCollection
 */
class SprintCollectionTest extends UnitTestCase
{
    /**
     * @var SprintCollection|Sprint[]
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new SprintCollection();
    }

    public function testShouldAddSprint()
    {
        $sprint     = $this->getMockSprint();

        $this->assertCount(0, $this->collection);
        $this->collection->addSprint($sprint);
        $this->assertCount(1, $this->collection);
        $this->collection->add($sprint);
        $this->assertCount(2, $this->collection);
    }

    public function testShouldFindTheSprint()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->add($sprint);
        $this->assertInstanceOfSprint($this->collection->findOneByName('name'));
    }
}