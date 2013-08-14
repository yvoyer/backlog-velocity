<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\MemberInterface;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\TeamMember
 */
class TeamMemberTest extends UnitTestCase
{
    /**
     * @param MemberInterface $member
     * @param TeamInterface   $team
     *
     * @return TeamMember
     */
    private function getTeamMember(MemberInterface $member = null, TeamInterface $team = null)
    {
        $member = $this->getMockMember($member);
        $team   = $this->getMockTeam($team);

        return new TeamMember($member, $team);
    }

    public function testShouldReturnTheConfiguredMember()
    {
        $member = $this->getMockMember();
        $this->assertSame($member, $this->getTeamMember($member)->getMember());
    }

    public function testShouldReturnTheConfiguredTeam()
    {
        $team = $this->getMockTeam();
        $this->assertSame($team, $this->getTeamMember(null, $team)->getTeam());
    }
}
