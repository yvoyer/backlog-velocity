<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Person;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Command\Person\ListPersonCommand;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Infrastructure\Cli\Command\CliIntegrationTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListPersonCommandTestCli extends CliIntegrationTestCase
{
    /**
     * @var ListPersonCommand
     */
    private $command;

    public function setUp()
    {
        $person = PersonModel::fromString('id', 'person');

        $repository = new PersonCollection(array($person, $person));
        $this->command = new ListPersonCommand($repository);
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
