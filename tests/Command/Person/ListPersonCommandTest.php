<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Person;

use Star\Component\Sprint\Command\Person\ListPersonCommand;
use tests\UnitTestCase;

/**
 * Class ListPersonCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Person
 */
class ListPersonCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var ListPersonCommand
     */
    private $command;

    public function setUp()
    {
        $this->repository = $this->getMockPersonRepository();
        $this->command = new ListPersonCommand($this->repository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:person:list', 'List all persons.');
    }

    public function test_should_list_all_persons()
    {
        $person = $this->getMockPerson();
        $person
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('person'));

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array($person, $person)));

        $content = $this->executeCommand($this->command, array());
        $expected = <<<CONTENT
List of available persons:
    person
    person
CONTENT;

        $this->assertContains($expected, $content);
    }
}
 