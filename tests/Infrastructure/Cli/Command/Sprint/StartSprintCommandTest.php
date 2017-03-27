<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Sprint;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Sprint\StartSprintCommand;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Symfony\Component\Console\Helper\HelperSet;
use tests\Stub\Sprint\StubSprint;
use tests\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
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
     * @var SprintCollection
     */
    private $sprintRepository;

    /**
     * @var StubSprint
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = StubSprint::withId(SprintId::fromString('name'));
        $this->sprintRepository = new SprintCollection();
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
        $this->sprint->withManDays(ManDays::fromInt(123));
        $this->assertSprintIsSaved();

        $this->assertFalse($this->sprint->isStarted());
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' is now started.", $result);
        $this->assertTrue($this->sprint->isStarted());
    }

    public function test_should_not_start_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Estimated velocity must be numeric.
     */
    public function test_should_throw_exception_when_no_estimated_velocity_given()
    {
        $this->sprint->withManDays(ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => ''));
    }

    public function test_should_use_dialog_to_set_estimated_cost()
    {
        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('askAndValidate')
            ->will($this->returnValue(123));
        $this->sprint->withManDays(ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->command->setHelperSet(new HelperSet(array('dialog' => $dialog)));
        $display = $this->executeCommand($this->command, array('name' => 'name'));
        $this->assertContains("I suggest:  man days.", $display);
        $this->assertContains("Sprint 'name' is now started.", $display);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The dialog helper is not configured.
     */
    public function test_should_throw_exception_when_dialog_not_set()
    {
        $this->sprint->withManDays(ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->command->setHelperSet(new HelperSet());
        $this->executeCommand($this->command, array('name' => 'name'));
    }

    private function assertSprintIsSaved()
    {
        $this->sprintRepository->saveSprint($this->sprint);
    }

    /**
     * @ticket #52
     */
    public function test_should_show_meaningful_message_when_no_man_days_available()
    {
        $this->assertSprintIsSaved();
        $this->sprint->withManDays(ManDays::fromInt(0));
        $this->assertSame(0, $this->sprint->getManDays()->toInt());

        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint member's commitments total should be greater than 0.", $result);
    }
}
