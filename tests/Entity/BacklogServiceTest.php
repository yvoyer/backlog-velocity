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
        $this->teamRepository = $this->getMock(TeamRepository::INTERFACE_NAME);

        $this->backlog = new BacklogService($this->teamRepository);
    }

    public function test_should_create_team()
    {
        $this->teamRepository
            ->expects($this->once())
            ->method('add')
            ->with('name');
        $this->teamRepository
            ->expects($this->once())
            ->method('save');

        $this->backlog->createTeam('name');
    }
}
 