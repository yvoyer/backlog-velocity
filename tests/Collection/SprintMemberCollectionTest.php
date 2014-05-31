<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Collection;

use Star\Component\Sprint\Collection\SprintMemberCollection;
use tests\UnitTestCase;

/**
 * Class SprintMemberCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Collection
 * @covers Star\Component\Sprint\Collection\SprintMemberCollection
 */
class SprintMemberCollectionTest extends UnitTestCase
{
    /**
     * @var SprintMemberCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new SprintMemberCollection();
    }

    public function testShouldContainSprintMembers()
    {
        $this->assertCount(0, $this->collection);
        $this->collection->addSprintMember($this->getMockSprintMember());
        $this->assertCount(1, $this->collection);
        $this->collection->addSprintMember($this->getMockSprintMember());
        $this->assertCount(2, $this->collection);
    }

    public function testShouldBeIterable()
    {
        $this->collection->addSprintMember($this->getMockSprintMember());
        foreach ($this->collection as $element) {
            $this->assertInstanceOfSprintMember($element);
        }
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $sprinter = $this->getMockSprintMember();
        $sprinter
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->addSprintMember($sprinter);
        $this->assertInstanceOfSprintMember($this->collection->findOneByName('name'));
    }
}
 