<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\AddCommand;
use tests\UnitTestCase;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\AddCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @var AddCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    public function setUp()
    {
        $this->teamRepository = $this->getMockTeamRepository();
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->command = new AddCommand($this->teamRepository, $this->sprintRepository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:sprint:add', 'Create a new sprint for the team.');
    }

    /**
     * @dataProvider provideSupportedArgumentData
     */
    public function test_should_have_an_argument($argument)
    {
        $this->assertCommandHasArgument($this->command, $argument, null, true);
    }

    public function provideSupportedArgumentData()
    {
        return array(
            array('name'),
            array('team'),
        );
    }

    /**
     * @depends test_should_be_a_command
     */
    public function test_should_persist_the_input_sprint_in_repository()
    {
        $sprintName = 'Sprint name';
        $teamName   = 'Team name';
        $sprint     = $this->getMockSprint();

        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('createSprint')
            ->with($sprintName)
            ->will($this->returnValue($sprint));

        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with($teamName)
            ->will($this->returnValue($team));

        $this->assertSprintIsSaved($sprint);

        $display = $this->executeCommand(
            $this->command,
            array(
                'name' => $sprintName,
                'team' => $teamName,
            )
        );
        $this->assertContains('The object was successfully saved.', $display);
    }

    /**
     * @param $sprint
     */
    private function assertSprintIsSaved($sprint)
    {
        $this->sprintRepository
            ->expects($this->once())
            ->method('add')
            ->with($sprint);
        $this->sprintRepository
            ->expects($this->once())
            ->method('save');
    }

    public function test_should_exit_when_team_not_found()
    {
        $display = $this->executeCommand(
            $this->command,
            array(
                'name' => 'sprint-name',
                'team' => 'team-name',
            )
        );
        $this->assertContains("The team 'team-name' cannot be found.", $display);
    }
}
