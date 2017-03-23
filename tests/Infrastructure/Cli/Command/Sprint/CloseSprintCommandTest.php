<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\CloseSprintCommand;
use tests\UnitTestCase;

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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    public function setUp()
    {
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->command = new CloseSprintCommand($this->sprintRepository);
    }

    public function test_should_close_the_sprint()
    {
        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('close')
            ->with(123);

        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('name')
            ->will($this->returnValue($sprint));

        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' is now closed.", $result);
    }

    public function test_should_not_close_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }

    public function test_should_save_the_sprint()
    {
        $sprint = $this->getMockSprint();

        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue($sprint));
        $this->sprintRepository
            ->expects($this->once())
            ->method('add');
        $this->sprintRepository
            ->expects($this->once())
            ->method('save');

        $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
    }
}
