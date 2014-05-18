<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class PersonModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
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

    public function testShouldReturnId()
    {
        $this->assertSame('name', (string) $this->person->getId());
    }

    public function testShouldBeAPerson()
    {
        $this->assertInstanceOfPerson($this->person);
    }

    /**
     * @depends testShouldBeAPerson
     */
    public function testShouldHaveAName()
    {
        $this->assertSame('name', $this->person->getName());
    }

    public function testShouldJoinTheTeam()
    {
        $this->assertInstanceOfTeamMember($this->person->joinTeam($this->team));
    }

    public function testShouldNotCreateATeamMemberWhenAlreadyMemberOfTeam()
    {
        $teamMember = $this->person->joinTeam($this->team);
        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertSame($teamMember, $this->person->joinTeam($this->team));
    }

    public function testShouldJoinTheSprint()
    {
        $this->assertInstanceOfSprintMember($this->person->joinSprint($this->sprint, 4));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\SprintException
     * @expectedExceptionMessage The person is already member of the sprint.
     */
    public function testShouldThrowExceptionWhenAlreadyJoinedSprint()
    {
        $this->person->joinSprint($this->sprint, 4);
        $this->person->joinSprint($this->sprint, 4);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function testShouldHaveAValidName()
    {
        new PersonModel('');
    }
}
 