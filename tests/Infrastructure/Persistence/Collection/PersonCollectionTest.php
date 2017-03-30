<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\PersonModel;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Collection\PersonCollection
 */
class PersonCollectionTest extends \PHPUnit_Framework_TestCase
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
        $this->collection->savePerson(PersonModel::fromString('id1', 'name'));
        $this->assertCount(1, $this->collection);
        $this->collection->savePerson(PersonModel::fromString('id2', 'name'));
        $this->assertCount(2, $this->collection);
    }

    public function testShouldFindTheTeam()
    {
        $this->assertNull($this->collection->findOneById(PersonId::fromString('id')));
        $person = PersonModel::fromString('id', 'name');
        $this->collection->savePerson($person);
        $this->assertSame($person, $this->collection->findOneById(PersonId::fromString('id')));
    }
}
