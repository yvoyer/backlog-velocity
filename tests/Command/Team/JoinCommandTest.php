<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Team;

use Star\Component\Sprint\Command\Team\JoinCommand;
use tests\UnitTestCase;

/**
 * Class JoinCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\JoinCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class JoinCommandTest extends UnitTestCase
{
    /**
     * @var JoinCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $personRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMemberRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMember;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->person = $this->getMockPerson();
        $this->teamMember = $this->getMockTeamMember();
        $this->team = $this->getMockTeam();

        $this->teamRepository = $this->getMockTeamRepository();
        $this->personRepository = $this->getMockPersonRepository();
        $this->teamMemberRepository = $this->getMockTeamMemberRepository();

        $this->command = new JoinCommand($this->teamRepository, $this->personRepository, $this->teamMemberRepository);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, JoinCommand::NAME, 'Link a person to a team.');
    }

    public function test_should_have_a_team_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinCommand::ARGUMENT_TEAM, null, true);
    }

    public function test_should_have_a_sprinter_argument()
    {
        $this->assertCommandHasArgument($this->command, JoinCommand::ARGUMENT_PERSON, null, true);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Person name must be supplied
     */
    public function test_should_throw_exception_when_person_empty()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => '',
            JoinCommand::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function test_should_throw_exception_when_team_empty()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'val',
            JoinCommand::ARGUMENT_TEAM => '',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\EntityNotFoundException
     * @expectedExceptionMessage The team could not be found.
     */
    public function test_should_throw_exception_when_team_not_found()
    {
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'sprinterName',
            JoinCommand::ARGUMENT_TEAM => 'teamName',
        );
        $this->executeCommand($this->command, $inputs);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\EntityNotFoundException
     * @expectedExceptionMessage The person could not be found.
     */
    public function test_should_throw_exception_when_person_not_found()
    {
        $this->assertTeamIsFound();
        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'sprinterName',
            JoinCommand::ARGUMENT_TEAM => 'teamName',
        );
        $this->executeCommand($this->command, $inputs);
    }

    public function test_should_save_using_the_found_team_and_sprinter()
    {
        $this->assertMemberIsAddedToTeam();
        $this->assertTeamIsFound();
        $this->assertPersonIsFound();
        $this->assertTeamMemberIsSaved();

        $inputs = array(
            JoinCommand::ARGUMENT_PERSON => 'sprinterName',
            JoinCommand::ARGUMENT_TEAM => 'teamName',
        );
        $this->executeCommand($this->command, $inputs);
    }

    private function assertTeamMemberIsSaved()
    {
        $this->teamMemberRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->teamMember);
        $this->teamMemberRepository
            ->expects($this->once())
            ->method('save');
    }

    private function assertMemberIsAddedToTeam()
    {
        $this->team
            ->expects($this->once())
            ->method('addTeamMember')
            ->with($this->person)
            ->will($this->returnValue($this->teamMember));
    }

    private function assertTeamIsFound()
    {
        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('teamName')
            ->will($this->returnValue($this->team));
    }

    private function assertPersonIsFound()
    {
        $this->personRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('sprinterName')
            ->will($this->returnValue($this->person));
    }
}
