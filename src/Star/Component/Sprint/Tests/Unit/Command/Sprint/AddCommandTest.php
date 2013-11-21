<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprint;

use Star\Component\Sprint\Command\Sprint\AddCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprint
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
     * @var EntityFinder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * @var EntityCreator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creator;

    /**
     * @var Repository
     */
    private $repository;

    public function setUp()
    {
        $this->finder     = $this->getMockEntityFinder();
        $this->creator    = $this->getMockEntityCreator();
        $this->repository = $this->getMockRepository();
        $this->sut = new AddCommand($this->repository, $this->creator, $this->finder);
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
            array('man-days'),
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
        $team       = $this->getMockTeam();
        $manDays    = 12;

        $this->creator
            ->expects($this->once())
            ->method('createSprint')
            ->with($sprintName, $team, $manDays)
            ->will($this->returnValue($sprint));

        $this->finder
            ->expects($this->once())
            ->method('findTeam')
            ->with($teamName)
            ->will($this->returnValue($team));

        $this->assertSprintIsSaved($sprint);

        $display = $this->executeCommand(
            $this->sut,
            array(
                '--name'     => $sprintName,
                '--team'     => $teamName,
                '--man-days' => $manDays,
            )
        );
        $this->assertContains('The object was successfully saved.', $display);
    }

    /**
     * @param $sprint
     */
    private function assertSprintIsSaved($sprint)
    {
        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($sprint);
        $this->repository
            ->expects($this->once())
            ->method('save');
    }
}
