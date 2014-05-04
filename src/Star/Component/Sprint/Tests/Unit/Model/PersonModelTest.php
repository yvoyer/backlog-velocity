<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class PersonModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
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

    public function testShouldBeAPerson()
    {
        $this->assertInstanceOfPerson($this->person);
    }

    /**
     * @depends testShouldBeAPerson
     */
    public function testShouldHaveAName()
    {
        $this->assertSame('name', $this->person->getName());
    }
}
 