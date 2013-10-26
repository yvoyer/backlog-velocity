<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\ListCommand;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\ListCommand
 */
class ListCommandTest extends UnitTestCase
{
    /**
     * @var SprintRepository|\PHPUnit_Framework_MockObject_MockObject
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
        $sprint = $this->getMockSprint();
        $sprint
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Sprint 1'));

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
 