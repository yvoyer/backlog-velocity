<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Team;

use Star\Component\Sprint\Command\Team\AddCommand;
use tests\UnitTestCase;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var AddCommand
     */
    private $command;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->teamRepository = $this->getMockTeamRepository();
        $this->factory = $this->getMockTeamFactory();
        $this->team = $this->getMockTeam();

        $this->command = new AddCommand($this->teamRepository, $this->factory);
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:team:add', 'Add a team');
    }

    public function test_should_have_a_name_argument()
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_use_the_argument_supplied_as_team_name()
    {
        $this->factory
            ->expects($this->once())
            ->method('createTeam')
            ->with('teamName')
            ->will($this->returnValue($this->team));

        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('teamName');
        $this->teamRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->team);
        $this->teamRepository
            ->expects($this->once())
            ->method('save');

        $content = $this->executeCommand($this->command, array('name' => 'teamName'));
        $this->assertContains("The team 'teamName' was successfully saved.", $content);
    }

    public function test_should_not_add_team_when_the_team_name_already_exists()
    {
        $this->factory
            ->expects($this->never())
            ->method('createTeam');
        $this->teamRepository
            ->expects($this->never())
            ->method('add');
        $this->teamRepository
            ->expects($this->never())
            ->method('save');
        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue($this->team));

        $content = $this->executeCommand($this->command, array('name' => 'teamName'));
        $this->assertContains("The team 'teamName' already exists.", $content);
    }
}
