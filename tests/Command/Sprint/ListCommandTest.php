<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\ListCommand;
use tests\UnitTestCase;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\ListCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class ListCommandTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    /**
     * @var ListCommand
     */
    private $sut;

    public function setUp()
    {
        $this->sprintRepository = $this->getMockSprintRepository();
        $this->sut = new ListCommand($this->sprintRepository);
    }

    public function testShouldHaveName()
    {
        $this->assertSame('backlog:sprint:list', $this->sut->getName());
    }

    public function testShouldHaveDescription()
    {
        $this->assertSame('List all available sprints.', $this->sut->getDescription());
    }

    public function testShouldShowTheFoundSprint()
    {
        $sprintMember = $this->getMockSprintMember();

        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Sprint 1'));
        $sprint
            ->expects($this->once())
            ->method('getSprintMembers')
            ->will($this->returnValue(array($sprintMember)));

        $this->sprintRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array($sprint)));

        $display = $this->executeCommand($this->sut);
        $this->assertContains('Sprint 1', $display);
    }

    public function testShouldShowNoSprint()
    {
        $this->sprintRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array()));

        $display = $this->executeCommand($this->sut);
        $this->assertContains('No sprints were found.', $display);
    }
}
 