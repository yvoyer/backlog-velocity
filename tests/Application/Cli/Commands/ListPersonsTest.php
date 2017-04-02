<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Model\PersonModel;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListPersonsTest extends CliIntegrationTestCase
{
    /**
     * @var ListPersons
     */
    private $command;

    public function setUp()
    {
        $person = PersonModel::fromString('id', 'person');

        $repository = new PersonCollection(array($person, $person));
        $this->command = new ListPersons($repository);
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
