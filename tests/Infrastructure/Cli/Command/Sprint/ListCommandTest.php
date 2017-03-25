<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Sprint\ListCommand;
use Star\Component\Sprint\Model\Builder\SprintBuilder;
use Star\Component\Sprint\Model\Identity\SprintId;
use tests\Stub\Sprint\StubSprint;
use tests\UnitTestCase;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Command\Sprint\ListCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class ListCommandTest extends UnitTestCase
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
        $this->assertContains('Sprint 1', $display);
    }

    public function testShouldShowNoSprint()
    {
        $this->sprintRepository->saveSprint(StubSprint::closed(SprintId::fromString('Sprint 1')));

        $display = $this->executeCommand($this->command);
        $this->assertContains('No sprints were found.', $display);
    }
}
