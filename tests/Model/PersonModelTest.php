<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace tests\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\PersonModel;
use tests\UnitTestCase;

/**
 * Class PersonModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package tests\Model
 *
 * @covers Star\Component\Sprint\Model\PersonModel
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Collection\TeamMemberCollection
 * @uses Star\Component\Sprint\Entity\Id\PersonId
 * @uses Star\Component\Sprint\Model\SprinterModel
 * @uses Star\Component\Sprint\Model\TeamMemberModel
 * @uses Star\Component\Sprint\Type\String
 */
class PersonModelTest extends UnitTestCase
{
    /**
     * @var PersonModel
     */
    private $person;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    /**
     * @var Sprint|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->sprint = $this->getMockSprint();

        $this->person = new PersonModel('name');
    }

    public function test_should_return_id()
    {
        $this->assertSame('name', (string) $this->person->getId());
    }

    public function test_should_be_a_person()
    {
        $this->assertInstanceOfPerson($this->person);
    }

    /**
     * @depends test_should_be_a_person
     */
    public function test_should_have_a_name()
    {
        $this->assertSame('name', $this->person->getName());
    }

    public function test_should_join_the_team()
    {
        $this->assertInstanceOfTeamMember($this->person->joinTeam($this->team));
    }

    public function test_should_not_create_a_team_member_when_already_member_of_team()
    {
        $teamMember = $this->person->joinTeam($this->team);
        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertSame($teamMember, $this->person->joinTeam($this->team));
    }

    public function test_should_join_the_sprint()
    {
        $this->assertInstanceOfSprintMember($this->person->joinSprint($this->sprint, 4));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\SprintException
     * @expectedExceptionMessage The person is already member of the sprint.
     */
    public function test_should_throw_exception_when_already_joined_sprint()
    {
        $this->person->joinSprint($this->sprint, 4);
        $this->person->joinSprint($this->sprint, 4);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new PersonModel('');
    }
}
 