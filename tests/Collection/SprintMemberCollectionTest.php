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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintMember;

    public function setUp()
    {
        $this->sprintMember = $this->getMockSprintMember();

        $this->collection = new SprintMemberCollection();
    }

    public function test_should_contain_sprint_members()
    {
        $this->assertCount(0, $this->collection);
        $this->collection->addSprintMember($this->sprintMember);
        $this->assertCount(1, $this->collection);
        $this->collection->addSprintMember($this->sprintMember);
        $this->assertCount(2, $this->collection);
    }

    public function test_should_be_iterable()
    {
        $this->collection->addSprintMember($this->sprintMember);
        foreach ($this->collection as $element) {
            $this->assertInstanceOfSprintMember($element);
        }
    }

    public function test_should_find_the_team()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $this->sprintMember
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->addSprintMember($this->sprintMember);
        $this->assertInstanceOfSprintMember($this->collection->findOneByName('name'));
    }

    public function test_should_filter_by_sprint()
    {
        $sprint = $this->getMockSprint();

        $this->sprintMember
            ->expects($this->once())
            ->method('getSprint')
            ->will($this->returnValue($sprint));
        $this->collection->addSprintMember($this->sprintMember);

        $this->assertSame($this->sprintMember, $this->collection->filterBySprint($sprint));
    }
}
 