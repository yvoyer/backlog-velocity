<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class ListPersonsTest extends CliIntegrationTestCase
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
