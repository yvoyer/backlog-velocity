<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\StartSprintCommand;
use Symfony\Component\Console\Helper\HelperSet;
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
 * @uses Star\Component\Sprint\Collection\SprintCollection
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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = $this->getMockSprint();
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->command = new StartSprintCommand($this->sprintRepository, $this->getMockCalculator());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockDialog()
    {
        return $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function test_should_start_the_sprint()
    {
        $this->sprint
            ->expects($this->once())
            ->method('start')
            ->with(123);
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(1));

        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('name')
            ->will($this->returnValue($this->sprint));

        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' is now started.", $result);
    }

    public function test_should_not_start_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }

    public function test_should_save_the_sprint()
    {
        $this->assertSprintIsSaved();
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(1));

        $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Estimated velocity must be numeric.
     */
    public function test_should_throw_exception_when_no_estimated_velocity_given()
    {
        $this->assertSprintIsFound();
        $this->sprint
            ->expects($this->never())
            ->method('start');
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(1));

        $this->command = new StartSprintCommand($this->sprintRepository, $this->getMockCalculator());
        $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => ''));
    }

    public function test_should_use_dialog_to_set_estimated_cost()
    {
        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('askAndValidate')
            ->will($this->returnValue(123));
        $this->assertSprintIsSaved();

        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getClosedSprints')
            ->will($this->returnValue(array()));

        $this->sprint
            ->expects($this->once())
            ->method('getTeam')
            ->will($this->returnValue($team));
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(1));

        $this->command = new StartSprintCommand($this->sprintRepository, $this->getMockCalculator());
        $this->command->setHelperSet(new HelperSet(array('dialog' => $dialog)));
        $this->executeCommand($this->command, array('name' => 'name'));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The dialog helper is not configured.
     */
    public function test_should_throw_exception_when_dialog_not_set()
    {
        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getClosedSprints')
            ->will($this->returnValue(array()));

        $this->sprint
            ->expects($this->once())
            ->method('getTeam')
            ->will($this->returnValue($team));
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(1));
        $this->assertSprintIsFound();

        $this->command = new StartSprintCommand($this->sprintRepository, $this->getMockCalculator());
        $this->command->setHelperSet(new HelperSet());
        $this->executeCommand($this->command, array('name' => 'name'));
    }

    private function assertSprintIsSaved()
    {
        $this->assertSprintIsFound();
        $this->sprintRepository
            ->expects($this->once())
            ->method('add');
        $this->sprintRepository
            ->expects($this->once())
            ->method('save');
    }

    private function assertSprintIsFound()
    {
        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue($this->sprint));
    }

    public function test_should_use_the_default_calculator()
    {
        $this->command = new StartSprintCommand($this->sprintRepository);
        $this->assertAttributeInstanceOf(
            'Star\Component\Sprint\Calculator\ResourceCalculator',
            'calculator',
            $this->command
        );
    }

    /**
     * @ticket #52
     */
    public function test_should_show_meaningful_message_when_no_man_days_available()
    {
        $this->assertSprintIsFound();
        $this->sprint
            ->expects($this->once())
            ->method('getManDays')
            ->will($this->returnValue(0));

        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint member's commitments total should be greater than 0.", $result);
    }
}
 