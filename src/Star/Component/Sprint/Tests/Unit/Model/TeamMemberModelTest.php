<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamMemberModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 *
 * @covers Star\Component\Sprint\Model\TeamMemberModel
 */
class TeamMemberModelTest extends UnitTestCase
{
    /**
     * @var Team
     */
    private $team;

    /**
     * @var Person
     */
    private $person;

    /**
     * @var TeamMemberModel
     */
    private $teamMember;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->person = $this->getMockPerson();

        $this->teamMember = new TeamMemberModel($this->team, $this->person);
    }

    public function testShouldReturnThePerson()
    {
        $this->assertSame($this->person, $this->teamMember->getPerson());
    }

    public function testShouldReturnTheTeam()
    {
        $this->assertSame($this->team, $this->teamMember->getTeam());
    }

    public function testShouldReturnWhetherTheTeamMemberIsEquals()
    {
        $this->assertTrue($this->teamMember->isEqual($this->teamMember));
        $this->assertFalse($this->teamMember->isEqual($this->getMockTeamMember()));
    }

    public function testShouldReturnWhetherThePersonIsEquals()
    {
        $this->assertTrue($this->teamMember->isEqual($this->person));
        $this->assertFalse($this->teamMember->isEqual($this->getMockPerson()));
    }
}
 