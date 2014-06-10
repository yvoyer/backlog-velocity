<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\JoinSprintCommand;
use tests\UnitTestCase;

/**
 * Class JoinSprintCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\JoinSprintCommand
 */
class JoinSprintCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMemberRepository;

    /**
     * @var JoinSprintCommand
     */
    private $command;

    public function setUp()
    {
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->teamMemberRepository = $this->getMockTeamMemberRepository();

        $this->command = new JoinSprintCommand($this->sprintRepository, $this->teamMemberRepository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:sprint:join', 'Make an person tart of a group.');
    }

    /**
     * @dataProvider provideArgumentsData
     */
    public function test_should_have_arguments($argument)
    {
        $this->assertCommandHasArgument($this->command, $argument, null, true);
    }

    public function provideArgumentsData()
    {
        return array(
            array(JoinSprintCommand::ARGUMENT_SPRINT),
            array(JoinSprintCommand::ARGUMENT_PERSON),
            array(JoinSprintCommand::ARGUMENT_MAN_DAYS),
        );
    }

    public function test_should_join_the_sprint()
    {
        $teamMember = $this->getMockTeamMember();

        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('commit')
            ->with($teamMember, 123);

        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue($sprint));

        $this->teamMemberRepository
            ->expects($this->once())
            ->method('findMemberOfSprint')
            ->with('person-name', 'sprint-name')
            ->will($this->returnValue($teamMember));

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The person 'person-name' is now committed to the 'sprint-name' sprint for '123' man days.",
            $content
        );
    }

    public function test_should_generate_an_error_when_sprint_not_found()
    {
        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains("The sprint 'sprint-name' can't be found.", $content);
    }

    public function test_should_generate_an_error_when_team_member_not_found()
    {
        $this->sprintRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue($this->getMockSprint()));

        $content = $this->executeCommand(
            $this->command,
            array(
                JoinSprintCommand::ARGUMENT_SPRINT => 'sprint-name',
                JoinSprintCommand::ARGUMENT_PERSON => 'person-name',
                JoinSprintCommand::ARGUMENT_MAN_DAYS => 123,
            )
        );
        $this->assertContains(
            "The team's member 'person-name' is not part of sprint 'sprint-name'.",
            $content
        );
    }
}
