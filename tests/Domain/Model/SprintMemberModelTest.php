<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Model\SprintMemberModel;
use tests\UnitTestCase;

/**
 * Class SprintMemberModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Model\SprintMemberModel
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

    public function test_should_support_available_man_days_as_string()
    {
        $this->sprintMember = new SprintMemberModel('12', $this->sprint, $this->teamMember);
        $this->assertSame('12', $this->sprintMember->getAvailableManDays(), 'Man days must support string int.');
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

    public function test_should_return_the_sprint()
    {
        $this->assertSame($this->sprint, $this->sprintMember->getSprint());
    }

    public function test_should_return_the_member()
    {
        $this->assertSame($this->teamMember, $this->sprintMember->getTeamMember());
    }

    /**
     * @ticket #57
     * @dataProvider provideInvalidManDays
     *
     * @param $manDays
     *
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The man days must be a numeric greater than zero.
     */
    public function test_should_throw_exception_when_invalid_man_days($manDays)
    {
        new SprintMemberModel($manDays, $this->sprint, $this->teamMember);
    }

    public function provideInvalidManDays()
    {
        return array(
            'Man days cannot be zero' => array(0),
            'Man days cannot be negative' => array(-1),
            'Man days cannot be array' => array(array()),
            'Man days cannot be bool false' => array(false),
            'Man days cannot be bool true' => array(true),
            'Man days cannot be string' => array(''),
            'Man days cannot be float' => array(213.321),
        );
    }
}
