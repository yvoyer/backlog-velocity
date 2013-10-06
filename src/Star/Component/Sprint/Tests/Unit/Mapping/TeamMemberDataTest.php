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
     * @param Sprinter $member
     * @param Team     $team
     *
     * @return TeamMemberData
     */
    private function getTeamMember(Sprinter $member = null, Team $team = null)
    {
        $member = $this->getMockSprinter($member);
        $team   = $this->getMockTeam($team);

        return new TeamMemberData($member, $team);
    }

    public function testShouldBeTeamMember()
    {
        $this->assertInstanceOfTeamMember($this->getTeamMember());
    }

    public function testShouldReturnTheConfiguredMember()
    {
        $member = $this->getMockSprinter();
        $this->assertSame($member, $this->getTeamMember($member)->getMember());
    }

    public function testShouldReturnTheConfiguredTeam()
    {
        $team = $this->getMockTeam();
        $this->assertSame($team, $this->getTeamMember(null, $team)->getTeam());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->getTeamMember());
    }
}
