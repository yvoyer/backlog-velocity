<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Team;

use Star\Component\Sprint\Command\Team\ListCommand;
use tests\UnitTestCase;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\ListCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class ListCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var ListCommand
     */
    private $command;

    public function setUp()
    {
        $this->repository = $this->getMockTeamRepository();

        $this->command = new ListCommand($this->repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:team:list', $this->command->getName());
    }

    public function testShouldHaveADescription()
    {
        $this->assertSame('List the teams', $this->command->getDescription());
    }

    public function testShouldListAllTeams()
    {
        $name = uniqid('name');
        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array($team)));

        $display = $this->executeCommand($this->command);

        $this->assertContains($name, $display);
    }
}
