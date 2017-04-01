<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Sprint\ListCommand;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Stub\Sprint\StubSprint;
use Star\Component\Sprint\IntegrationTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListCommandTest extends IntegrationTestCase
{
    /**
     * @var SprintCollection
     */
    private $sprintRepository;

    /**
     * @var ListCommand
     */
    private $command;

    public function setUp()
    {
        $this->sprintRepository = new SprintCollection();
        $this->command = new ListCommand($this->sprintRepository);
    }

    public function testShouldHaveName()
    {
        $this->assertSame('backlog:sprint:list', $this->command->getName());
    }

    public function testShouldHaveDescription()
    {
        $this->assertSame('List all available sprints.', $this->command->getDescription());
    }

    public function testShouldShowTheFoundSprint()
    {
        $sprint = StubSprint::withId(SprintId::fromString('Sprint 1'))
            ->active()
            ->withCommitment(12, 'person-id');

        $this->sprintRepository->saveSprint($sprint);

        $display = $this->executeCommand($this->command);
        $expected = <<<DISPLAY
List of available sprints:
+----------+-----------+------------+
| Sprint   | Members   | Commitment |
+----------+-----------+------------+
| Sprint 1 |           |            |
|          | person-id | 12         |
+----------+-----------+------------+

DISPLAY;

        $this->assertSame($expected, $display);
    }

    public function testShouldShowNoSprint()
    {
        $display = $this->executeCommand($this->command);
        $expected = <<<DISPLAY
List of available sprints:
No sprints were found.
+--------+---------+------------+
| Sprint | Members | Commitment |
+--------+---------+------------+

DISPLAY;
        $this->assertSame($expected, $display);
    }
}
