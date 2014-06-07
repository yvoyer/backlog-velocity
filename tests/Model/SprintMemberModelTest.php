<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Model\SprintMemberModel;
use tests\UnitTestCase;

/**
 * Class SprintMemberModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 */
class SprintMemberModelTest extends UnitTestCase
{
    /**
     * @var SprintMemberModel
     */
    private $sprintMember;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $teamMember;

    public function setUp()
    {
        $this->sprint = $this->getMockSprint();
        $this->sprint
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('sprintId'));

        $this->teamMember = $this->getMockTeamMember();
        $this->teamMember
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('personId'));

        $this->sprintMember = new SprintMemberModel(12, $this->sprint, $this->teamMember);
    }

    public function test_should_be_sprint_member()
    {
        $this->assertInstanceOfSprintMember($this->sprintMember);
    }

    public function test_should_return_the_available_man_days()
    {
        $this->assertSame(12, $this->sprintMember->getAvailableManDays());
    }

    public function test_should_return_id()
    {
        $this->assertNull($this->sprintMember->getId());
    }

    public function test_should_return_the_name()
    {
        $this->teamMember
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('sprinter-name'));

        $this->assertSame('sprinter-name', $this->sprintMember->getName());
    }
}
 