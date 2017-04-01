<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class PersonModelTest extends UnitTestCase
{
    /**
     * @var PersonModel
     */
    private $person;

    public function setUp()
    {
        $this->person = new PersonModel(PersonId::fromString('id'), new PersonName('name'));
    }

    public function test_should_return_id()
    {
        $this->assertSame('id', $this->person->getId()->toString());
    }

    public function test_should_be_a_person()
    {
        $this->assertInstanceOfPerson($this->person);
    }

    /**
     * @depends test_should_be_a_person
     */
    public function test_should_have_a_name()
    {
        $this->assertSame('name', $this->person->getName());
    }

    /**
     * @expectedException        \Assert\InvalidArgumentException
     * @expectedExceptionMessage Person name "" is empty, but non empty value was expected.
     */
    public function test_should_have_a_valid_name()
    {
        new PersonModel(PersonId::fromString('id'), new PersonName(''));
    }
}
