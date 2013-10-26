<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\UpdateCommand;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class UpdateCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprint
 *
 * @covers Star\Component\Sprint\Command\Sprint\UpdateCommand
 */
class UpdateCommandTest extends UnitTestCase
{
    const SEARCH_NAME = 'search name';
    const NEW_NAME = 'new name';

    /**
     * @var UpdateCommand
     */
    private $sut;

    /**
     * @var SprintRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var EntityFinder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * @var Sprint|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    public function setUp()
    {
        $this->repository = $this->getMockSprintRepository();
        $this->finder     = $this->getMockEntityFinder();
        $this->sprint     = $this->getMockSprint();

        $this->sut        = new UpdateCommand($this->finder, $this->repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:sprint:update', $this->sut->getName());
    }

    public function testShouldUpdateTheSprint()
    {
        $this->assertSprintIsFound();
        $this->assertSprintIsUpdated();

        $this->sprint
            ->expects($this->once())
            ->method('setName')
            ->with(self::NEW_NAME);
        $this->sprint
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $display = $this->executeCommand($this->sut, array('--search' => self::SEARCH_NAME, '--name' => self::NEW_NAME));
        $this->assertContains('The sprint was updated successfully.', $display);
    }

    public function testShouldNotPerformAnyChangesWhenSprintDataNotValid()
    {
        $this->assertSprintIsFound();
        $this->assertSprintNotUpdated();

        $this->sprint
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $display = $this->executeCommand($this->sut, array('--search' => self::SEARCH_NAME));
        $this->assertContains('The sprint contains invalid data', $display);
    }

    public function testShouldNotUpdateTheSprintWhenTheSprintIsNotFound()
    {
        $this->assertSprintNotFound();
        $this->assertSprintNotUpdated();

        $display = $this->executeCommand($this->sut, array('--search' => self::SEARCH_NAME));
        $this->assertContains("Sprint '" . self::SEARCH_NAME . "' was not found.", $display);
    }

    private function assertSprintNotUpdated()
    {
        $this->repository
            ->expects($this->never())
            ->method('add');
        $this->repository
            ->expects($this->never())
            ->method('save');
    }

    private function assertSprintIsUpdated()
    {
        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->sprint);
        $this->repository
            ->expects($this->once())
            ->method('save');
    }

    private function assertSprintIsFound()
    {
        $this->finder
            ->expects($this->once())
            ->method('findSprint')
            ->with(self::SEARCH_NAME)
            ->will($this->returnValue($this->sprint));
    }

    private function assertSprintNotFound()
    {
        $this->finder
            ->expects($this->once())
            ->method('findSprint')
            ->with(self::SEARCH_NAME);
    }
}
 