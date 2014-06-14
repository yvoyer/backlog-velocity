<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\StartSprintCommand;
use tests\UnitTestCase;

/**
 * Class StartSprintCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\StartSprintCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class StartSprintCommandTest extends UnitTestCase
{
    /**
     * @var StartSprintCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    public function setUp()
    {
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->command = new StartSprintCommand($this->sprintRepository);
    }

    public function test_should_start_the_sprint()
    {
        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('start')
            ->with(123);

        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('name')
            ->will($this->returnValue($sprint));

        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' is now started.", $result);
    }

    public function test_should_not_start_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }
}
 