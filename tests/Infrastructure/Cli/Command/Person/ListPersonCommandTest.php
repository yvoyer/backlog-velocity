<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Person;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Command\Person\ListPersonCommand;
use tests\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Command\Person\ListPersonCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class ListPersonCommandTest extends UnitTestCase
{
    /**
     * @var ListPersonCommand
     */
    private $command;

    public function setUp()
    {
        $person = $this->getMockPerson();
        $person
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('person'));

        $repository = new PersonCollection(array($person, $person));
        $this->command = new ListPersonCommand($repository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:person:list', 'List all persons.');
    }

    public function test_should_list_all_persons()
    {
        $content = $this->executeCommand($this->command, array());
        $expected = <<<CONTENT
List of available persons:
  * person
  * person
CONTENT;

        $this->assertContains($expected, $content);
    }
}
