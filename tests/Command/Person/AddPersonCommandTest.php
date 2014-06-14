<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Person;

use Star\Component\Sprint\Command\Person\AddPersonCommand;
use tests\UnitTestCase;

/**
 * Class AddPersonCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Person
 *
 * @covers Star\Component\Sprint\Command\Person\AddPersonCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class AddPersonCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $personRepository;

    /**
     * @var AddPersonCommand
     */
    private $command;

    public function setUp()
    {
        $this->person = $this->getMockPerson();
        $this->personRepository = $this->getMockPersonRepository();
        $this->factory = $this->getMockTeamFactory();

        $this->command = new AddPersonCommand($this->personRepository, $this->factory);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:person:add', 'Add a person.');
    }

    public function test_should_have_a_name_argument()
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_add_person()
    {
        $this->personRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->person);
        $this->personRepository
            ->expects($this->once())
            ->method('save');

        $this->factory
            ->expects($this->once())
            ->method('createPerson')
            ->with('person-name')
            ->will($this->returnValue($this->person));

        $content = $this->executeCommand($this->command, array('name' => 'person-name'));
        $this->assertContains("The person 'person-name' was successfully saved.", $content);
    }

    public function test_should_not_add_person_when_already_exists()
    {
        $this->personRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('person-name')
            ->will($this->returnValue($this->person));
        $this->personRepository
            ->expects($this->never())
            ->method('add');
        $this->personRepository
            ->expects($this->never())
            ->method('save');

        $this->factory
            ->expects($this->never())
            ->method('createPerson');

        $content = $this->executeCommand($this->command, array('name' => 'person-name'));
        $this->assertContains("The person 'person-name' already exists.", $content);
    }
}
 