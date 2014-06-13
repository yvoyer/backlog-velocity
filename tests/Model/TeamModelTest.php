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
        $this->assertNull($this->team->getId());
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->team->getName());
    }

    public function test_should_add_member()
    {
        $this->assertCount(0, $this->team->getTeamMembers());
        $this->assertFalse($this->team->hasTeamMember('person-name'));

        $teamMember = $this->team->addTeamMember($this->person);

        $this->assertTrue($this->team->hasTeamMember('person-name'));
        $this->assertCount(1, $this->team->getTeamMembers());

        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertContainsOnly(TeamMemberModel::CLASS_NAME, $this->team->getTeamMembers());
    }

    /**
     * @depends test_should_add_member
     */
    public function test_should_not_add_an_already_added_member()
    {
        $this->assertFalse($this->team->hasTeamMember('person-name'));
        $this->team->addTeamMember($this->person);
        $this->assertTrue($this->team->hasTeamMember('person-name'));
        $this->assertAttributeCount(1, 'teamMembers', $this->team);
        $this->team->addTeamMember($this->person);
        $this->assertAttributeCount(1, 'teamMembers', $this->team);
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

    public function test_should_return_a_new_sprint()
    {
        $this->assertInstanceOfSprint($this->team->createSprint('sprint'));
    }

    public function test_should_return_closed_sprints()
    {
        $startedSprint = $this->getMockSprint();

        $endedSprint = $this->getMockSprint();
        $endedSprint
            ->expects($this->once())
            ->method('isClosed')
            ->will($this->returnValue(true));

        $class = new \ReflectionClass($this->team);
        $property = $class->getProperty('sprints');
        $property->setAccessible(true);
        $property->setValue($this->team, array($startedSprint, $endedSprint));

        $this->assertSame(array($endedSprint), $this->team->getClosedSprints());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The person name must be string.
     */
    public function test_should_throw_exception_when_name_is_invalid()
    {
        $this->team->hasTeamMember(null);
    }
}
 