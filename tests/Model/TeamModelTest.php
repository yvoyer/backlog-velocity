<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use tests\UnitTestCase;

/**
 * Class TeamModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 *
 * @covers Star\Component\Sprint\Model\TeamModel
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Collection\SprintCollection
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Entity\Id\SprintId
 * @uses Star\Component\Sprint\Entity\Id\TeamId
 * @uses Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Model\SprinterModel
 * @uses Star\Component\Sprint\Model\TeamMemberModel
 * @uses Star\Component\Sprint\Type\String
 */
class TeamModelTest extends UnitTestCase
{
    /**
     * @var TeamModel
     */
    private $team;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = $this->getMockCalculator();
        $this->sprint = $this->getMockSprint();
        $this->sprint
            ->expects($this->any())
            ->method('getName')
            ->willReturn('sprint-name');

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
        $this->team->addSprintMember($this->person, $this->sprint, 10);
        $this->assertSame(10, $this->team->getAvailableManDays());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage There should be at least one team member.
     */
    public function test_should_throw_exception_when_no_team_member()
    {
        $this->team->startSprint($this->sprint, $this->calculator);
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
        $this->assertInstanceOfSprint($this->team->createSprint('name'));
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
     * @uses Star\Component\Sprint\Calculator\FocusCalculator
     */
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

    public function test_should_add_sprint_member_to_sprint()
    {
        $this->assertPersonIsPartOfTeam();
        $this->assertAttributeCount(0, 'sprinters', $this->team);
        $sprintMember = $this->team->addSprintMember($this->person, $this->sprint, 4);
        $this->assertInstanceOfSprintMember($sprintMember);
        $this->assertAttributeCount(1, 'sprinters', $this->team);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint member 'person-name' is already added.
     */
    public function test_should_throw_exception_when_sprint_member_already_added()
    {
        $this->assertPersonIsPartOfTeam();
        $this->team->addSprintMember($this->person, $this->sprint, 43);
        $this->team->addSprintMember($this->person, $this->sprint, 43);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The person 'person-name' is not member of team.
     */
    public function test_should_throw_exception_when_person_is_not_team_member()
    {
        $this->team->addSprintMember($this->person, $this->sprint, 43);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint 'sprint-name' is already started, no sprint member can be added.
     */
    public function test_should_throw_exception_when_sprint_is_already_started()
    {
        $this->sprint
            ->expects($this->once())
            ->method('isStarted')
            ->will($this->returnValue(true));

        $this->team->addSprintMember($this->person, $this->sprint, 43);
    }

    public function test_should_return_a_new_sprint()
    {
        $sprint = $this->team->createSprint('sprint');
        $this->assertInstanceOfSprint($sprint);
    }

    /**
     * @return \Star\Component\Sprint\Entity\Sprint
     */
    private function assertSprintIsStarted()
    {
        $this->assertPersonIsPartOfTeam();
        $this->calculator
            ->expects($this->atLeastOnce())
            ->method('calculateEstimatedVelocity')
            ->will($this->returnValue(43));
        $sprint = $this->team->createSprint('name');
        $this->team->startSprint($sprint, $this->calculator);

        return $sprint;
    }

    private function assertPersonIsPartOfTeam()
    {
        $this->team->addMember($this->person);
    }
}
 