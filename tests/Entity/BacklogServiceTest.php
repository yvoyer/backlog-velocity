<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Entity;

use Star\Component\Sprint\Entity\BacklogService;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;

/**
 * Class BacklogServiceTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Entity
 */
class BacklogServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BacklogService
     */
    private $backlog;

    /**
     * @var TeamRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $teamRepository;

    /**
     * @var PersonRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $personRepository;

    /**
     * @var SprintRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintRepository;

    /**
     * @var TeamMemberRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMemberRepository;

    /**
     * @var SprintMemberRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sprintMemberRepository;

    public function setUp()
    {
        $this->personRepository = $this->getMock(PersonRepository::INTERFACE_NAME);
        $this->teamRepository = $this->getMock(TeamRepository::INTERFACE_NAME);
        $this->sprintRepository = $this->getMock(SprintRepository::INTERFACE_NAME);
        $this->teamMemberRepository = $this->getMock(TeamMemberRepository::INTERFACE_NAME);
        $this->sprintMemberRepository = $this->getMock(SprintMemberRepository::INTERFACE_NAME);

        $this->backlog = new BacklogService(
            $this->personRepository,
            $this->sprintRepository,
            $this->teamRepository,
            $this->teamMemberRepository,
            $this->sprintMemberRepository
        );
    }

    public function test_should_create_team()
    {
        $this->teamRepository
            ->expects($this->once())
            ->method('add');
        $this->teamRepository
            ->expects($this->once())
            ->method('save');

        $this->assertInstanceOf(Team::INTERFACE_NAME, $this->backlog->createTeam('name'));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\EntityAlreadyExistsException
     * @expectedExceptionMessage The team already exists.
     */
    public function test_should_not_create_team()
    {
        $this->teamRepository
            ->expects($this->never())
            ->method('add');
        $this->teamRepository
            ->expects($this->never())
            ->method('save');
        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->will($this->returnValue('dssa'));

        $this->backlog->createTeam('name');
    }
}
 