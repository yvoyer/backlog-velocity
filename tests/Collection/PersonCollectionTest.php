<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Collection;

use Star\Component\Sprint\Collection\PersonCollection;
use tests\UnitTestCase;

/**
 * Class PersonCollectionTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Collection
 *
 * @covers Star\Component\Sprint\Collection\PersonCollection
 */
class PersonCollectionTest extends UnitTestCase
{
    /**
     * @var PersonCollection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new PersonCollection();
    }

    public function testShouldBeCountable()
    {
        $this->assertInstanceOf('\Countable', $this->collection);
    }

    /**
     * @depends testShouldBeCountable
     */
    public function testShouldManagePerson()
    {
        $this->assertEmpty($this->collection);
        $this->collection->addPerson($this->getMockPerson());
        $this->assertCount(1, $this->collection);
        $this->collection->add($this->getMockPerson());
        $this->assertCount(2, $this->collection);
    }

    /**
     * @depends testShouldManagePerson
     */
    public function testShouldBeIterator()
    {
        $iterate = false;
        $this->collection->add($this->getMockPerson());
        foreach ($this->collection as $element) {
            $iterate = true;
        }

        $this->assertTrue($iterate, 'The class should be iterable');
    }

    public function testShouldBeAPersonRepository()
    {
        $this->assertInstanceOfPersonRepository($this->collection);
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneByName(''));
        $person = $this->getMockPerson();
        $person
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('name'));
        $this->collection->add($person);
        $this->assertInstanceOfPerson($this->collection->findOneByName('name'));
    }
}
 