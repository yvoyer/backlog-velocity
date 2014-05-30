<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\UpdateCommand;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use tests\UnitTestCase;

/**
 * Class UpdateCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Command\Sprint
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
     * @var Sprint|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    public function setUp()
    {
        $this->repository = $this->getMockSprintRepository();
        $this->sprint = $this->getMockSprint();

        $this->sut = new UpdateCommand($this->repository);
    }

    public function test_should_have_a_name()
    {
        $this->assertSame('backlog:sprint:update', $this->sut->getName());
    }

    public function test_should_update_the_sprint()
    {
        $this->assertSprintIsFound();
        $this->assertSprintIsUpdated();

        $this->sprint
            ->expects($this->once())
            ->method('setName')
            ->with(self::NEW_NAME);

        $display = $this->executeCommand($this->sut, array('search' => self::SEARCH_NAME, 'name' => self::NEW_NAME));
        $this->assertContains('The sprint was updated successfully.', $display);
    }

    public function test_should_not_update_the_sprint_when_the_sprint_is_not_found()
    {
        $this->assertSprintNotFound();
        $this->repository
            ->expects($this->never())
            ->method('add');
        $this->repository
            ->expects($this->never())
            ->method('save');

        $display = $this->executeCommand($this->sut, array('search' => self::SEARCH_NAME, 'name' => 'new'));
        $this->assertContains("Sprint '" . self::SEARCH_NAME . "' was not found.", $display);
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
        $this->repository
            ->expects($this->once())
            ->method('findOneByName')
            ->with(self::SEARCH_NAME)
            ->will($this->returnValue($this->sprint));
    }

    private function assertSprintNotFound()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneByName')
            ->with(self::SEARCH_NAME);
    }
}
 