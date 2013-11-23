<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @var TeamRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var EntityCreator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creator;

    /**
     * @var EntityFinder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $finder;

    /**
     * @var AddCommand
     */
    private $sut;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->repository = $this->getMockTeamRepository();
        $this->creator    = $this->getMockEntityCreator();
        $this->finder     = $this->getMockEntityFinder();
        $this->team       = $this->getMockTeam();

        $this->sut = new AddCommand($this->repository, $this->creator, $this->finder);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->sut, 'backlog:team:add', 'Add a team');
    }

    public function testShouldHaveANameArgument()
    {
        $this->assertCommandHasArgument($this->sut, 'name');
    }

    public function testShouldUseTheArgumentSuppliedAsTeamName()
    {
        $this->creator
            ->expects($this->once())
            ->method('createTeam')
            ->with('teamName')
            ->will($this->returnValue($this->team));

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->team);
        $this->repository
            ->expects($this->once())
            ->method('save');


        $content = $this->executeCommand($this->sut, array('name' => 'teamName'));
        $this->assertContains('The object was successfully saved.', $content);
    }

    public function testShouldNotAddTeamWhenTheTeamNameAlreadyExists()
    {
        $this->creator
            ->expects($this->never())
            ->method('createTeam');

        $this->finder
            ->expects($this->once())
            ->method('findTeam')
            ->with('teamName')
            ->will($this->returnValue($this->team));

        $content = $this->executeCommand($this->sut, array('name' => 'teamName'));
        $this->assertContains('The team already exists.', $content);
    }
}
