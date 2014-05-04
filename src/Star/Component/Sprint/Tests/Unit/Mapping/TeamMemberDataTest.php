<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamMemberDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\TeamMemberData
 */
class TeamMemberDataTest extends UnitTestCase
{
    /**
     * @var Sprinter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $member;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    /**
     * @var TeamMemberData
     */
    private $sut;

    public function setUp()
    {
        $this->member = $this->getMockPerson();
        $this->team   = $this->getMockTeam();

        $this->sut = new TeamMemberData($this->member, $this->team);
    }

    public function testShouldBeTeamMember()
    {
        $this->assertInstanceOfTeamMember($this->sut);
    }

    public function testShouldReturnTheConfiguredMember()
    {
        $this->assertSame($this->member, $this->sut->getPerson());
    }

    public function testShouldReturnTheConfiguredTeam()
    {
        $this->assertSame($this->team, $this->sut->getTeam());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->sut);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testShouldBeValid()
    {
        // @todo Implement correclty
        $this->sut->isValid();
    }

    public function testShouldHaveAvailableManDays()
    {
        $this->sut->setAvailableManDays(3);
        $this->assertSame(3, $this->sut->getAvailableManDays());
    }
}
