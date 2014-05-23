<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 *
 * @covers Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Collection\SprintCollection
 * @uses Star\Component\Sprint\Collection\SprinterCollection
 * @uses Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Model\TeamMemberModel
 * @uses Star\Component\Sprint\Entity\Id\TeamId
 * @uses Star\Component\Sprint\Type\String
 */
class TeamModelTest extends UnitTestCase
{
    /**
     * @var TeamModel
     */
    private $team;

    /**
     * @var Person|\PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = $this->getMockCalculator();
        $this->person = $this->getMockPerson();
        $this->person
            ->expects($this->any())
            ->method('getName')
            ->willReturn('person-name');

        $this->team = new TeamModel('name');
    }

    public function test_should_return_the_id()
    {
        $this->assertSame('name', (string) $this->team->getId());
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->team->getName());
    }

    public function test_should_return_the_available_man_days()
    {
        $this->assertSame(0, $this->team->getAvailableManDays());
        $this->team->addMember($this->person);
        $this->team->setAvailability('person-name', 10);
        $this->assertSame(10, $this->team->getAvailableManDays());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least one team member.
     */
    public function test_should_throw_exception_when_no_team_member()
    {
        $this->team->startSprint('sprint-name', $this->calculator);
    }

    public function test_should_add_member()
    {
        $this->assertAttributeCount(0, 'members', $this->team);
        $teamMember = $this->team->addMember($this->person);
        $this->assertAttributeCount(1, 'members', $this->team);
        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertAttributeContainsOnly(TeamMemberModel::CLASS_NAME, 'members', $this->team);
    }

    /**
     * @depends test_should_add_member
     */
    public function test_should_not_add_an_already_added_member()
    {
        $this->assertFalse($this->team->hasPerson($this->person));
        $this->team->addMember($this->person);
        $this->assertTrue($this->team->hasPerson($this->person));
        $this->assertAttributeCount(1, 'members', $this->team);
        $this->team->addMember($this->person);
        $this->assertAttributeCount(1, 'members', $this->team);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new TeamModel('');
    }

    public function test_should_return_a_sprint()
    {
        $this->team->addMember($this->person);
        $this->team->setAvailability('person-name', 4);
        $this->assertInstanceOfSprint($this->team->startSprint('name', $this->calculator));
    }

    /**
     * @depends test_should_return_a_sprint
     */
    public function test_should_return_a_sprint_configured_with_estimated_velocity()
    {
        $sprint = $this->assertSprintIsStarted();

        $this->assertSame(43, $sprint->getEstimatedVelocity());
        $this->assertTrue($sprint->isStarted(), 'The sprint should be started');
    }

    public function test_should_contain_started_sprints()
    {
        $sprint = $this->assertSprintIsStarted();
        $this->assertAttributeContains($sprint, 'sprints', $this->team);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The team member 'unknown-member' was not found.
     */
    public function test_should_throw_exception_when_sprinter_not_found()
    {
        $this->team->setAvailability('unknown-member', 4);
    }

    public function test_close_sprint()
    {
        $this->assertSprintIsStarted();
        $sprint = $this->team->closeSprint('name', 54);
        $this->assertInstanceOfSprint($sprint);
        $this->assertTrue($sprint->isClosed(), 'Sprint should be closed');
        $this->assertSame(54, $sprint->getActualVelocity(), 'Sprint actual velocity is wrong');
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint 'not-found' was not found.
     */
    public function test_should_throw_exception_when_sprint_not_found()
    {
        $this->team->closeSprint('not-found', 43);
    }

    /**
     * @return \Star\Component\Sprint\Entity\Sprint
     */
    private function assertSprintIsStarted()
    {
        $this->team->addMember($this->person);
        $this->calculator
            ->expects($this->atLeastOnce())
            ->method('calculateEstimatedVelocity')
            ->will($this->returnValue(43));
        $sprint = $this->team->startSprint('name', $this->calculator);

        return $sprint;
    }
}
 