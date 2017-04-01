<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Sprint\CloseSprintCommand;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Stub\Sprint\StubSprint;
use Star\Component\Sprint\UnitTestCase;

/**
 * Class CloseSprintCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Command\Sprint\CloseSprintCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class CloseSprintCommandTest extends UnitTestCase
{
    /**
     * @var CloseSprintCommand
     */
    private $command;

    /**
     * @var SprintCollection
     */
    private $sprintRepository;

    public function setUp()
    {
        $this->sprintRepository = new SprintCollection();
        $this->command = new CloseSprintCommand($this->sprintRepository);
    }

    public function test_should_close_the_sprint()
    {
        $sprint = StubSprint::withId(SprintId::fromString('name'));
        $this->sprintRepository->saveSprint($sprint);

        $this->assertFalse($sprint->isClosed());
        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' is now closed.", $result);
        $this->assertTrue($sprint->isClosed());
    }

    public function test_should_not_close_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }
}
