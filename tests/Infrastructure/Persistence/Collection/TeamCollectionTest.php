<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\TeamCollection;
use tests\UnitTestCase;

/**
 * Class TeamCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\TeamCollection
 */
class TeamCollectionTest extends UnitTestCase
{
    /**
     * @var TeamCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new TeamCollection();
    }

    public function testShouldBeCountable()
    {
        $this->assertInstanceOf('\Countable', $this->collection);
    }

    /**
     * @depends testShouldBeCountable
     */
    public function testShouldManageTeam()
    {
        $this->assertEmpty($this->collection);
        $this->collection->saveTeam($this->getMockTeam());
        $this->assertCount(1, $this->collection);
        $this->collection->saveTeam($this->getMockTeam());
        $this->assertCount(2, $this->collection);
    }

    /**
     * @depends testShouldManageTeam
     */
    public function testShouldBeIterator()
    {
        $iterate = false;
        $this->collection->saveTeam($this->getMockTeam());
        foreach ($this->collection as $element) {
            $iterate = true;
        }

        $this->assertTrue($iterate, 'The class should be iterable');
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->saveTeam($team);
        $this->assertInstanceOfTeam($this->collection->findOneByName('name'));
    }
}
