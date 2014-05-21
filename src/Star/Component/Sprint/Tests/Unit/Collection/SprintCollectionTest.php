<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Collection;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Collection
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

        $this->assertCount(0, $this->collection->all());
        $this->collection->add($sprint);
        $this->assertCount(1, $this->collection->all());
    }

    public function testShouldBeASprintRepository()
    {
        $this->assertInstanceOfSprintRepository($this->collection);
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->add($team);
        $this->assertInstanceOfTeam($this->collection->findOneByName('name'));
    }
}
