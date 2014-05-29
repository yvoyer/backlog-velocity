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
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @var AddCommand
     */
    private $sut;

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
        $this->sut = new AddCommand($this->teamRepository, $this->sprintRepository);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->sut, 'backlog:sprint:add', 'Create a new sprint for the team.');
    }

    /**
     * @dataProvider provideSupportedOptionsData
     */
    public function testShouldHaveAnOption($option)
    {
        $this->assertCommandHasOption($this->sut, $option);
    }

    public function provideSupportedOptionsData()
    {
        return array(
            array('name'),
            array('team'),
        );
    }

    /**
     * @depends testShouldBeACommand
     */
    public function testShouldPersistTheInputSprintInRepository()
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
            $this->sut,
            array(
                '--name'     => $sprintName,
                '--team'     => $teamName,
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
}
