<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Id\PersonId;
use Star\Component\Sprint\Entity\Id\TeamId;
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
 */
class TeamModelTest extends UnitTestCase
{
    /**
     * @var TeamModel
     */
    private $sut;

    /**
     * @var Person|\PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    public function setUp()
    {
        $this->person = $this->getMockPerson();
        $this->sut = new TeamModel('name');
    }

    public function testShouldReturnTheId()
    {
        $this->assertSame('name', (string) $this->sut->getId());
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('name', $this->sut->getName());
    }

    public function testShouldReturnTheAvailableManDays()
    {
        $this->assertSame(0, $this->sut->getAvailableManDays());
    }

    public function testShouldAddMember()
    {
        $this->assertAttributeCount(0, 'members', $this->sut);
        $teamMember = $this->sut->addMember($this->person);
        $this->assertAttributeCount(1, 'members', $this->sut);
        $this->assertInstanceOfTeamMember($teamMember);
        $this->assertAttributeContainsOnly(TeamMemberModel::CLASS_NAME, 'members', $this->sut);
    }

    /**
     * @depends testShouldAddMember
     */
    public function testShouldNotAddAnAlreadyAddedMember()
    {
        $this->assertFalse($this->sut->hasPerson($this->person));
        $this->sut->addMember($this->person);
        $this->assertTrue($this->sut->hasPerson($this->person));
        $this->assertAttributeCount(1, 'members', $this->sut);
        $this->sut->addMember($this->person);
        $this->assertAttributeCount(1, 'members', $this->sut);
    }
}
 