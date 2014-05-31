<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Model\PersonModel;
use tests\UnitTestCase;

/**
 * Class PersonModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 *
 * @covers Star\Component\Sprint\Model\PersonModel
 * @uses Star\Component\Sprint\Entity\Id\PersonId
 * @uses Star\Component\Sprint\Type\String
 */
class PersonModelTest extends UnitTestCase
{
    /**
     * @var PersonModel
     */
    private $person;

    public function setUp()
    {
        $this->person = new PersonModel('name');
    }

    public function test_should_return_id()
    {
        $this->assertNull($this->person->getId());
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
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new PersonModel('');
    }
}
 