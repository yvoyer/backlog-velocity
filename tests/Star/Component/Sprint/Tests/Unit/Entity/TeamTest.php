<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\Team
 */
class TeamTest extends UnitTestCase
{
    /**
     * @param string $name
     *
     * @return Team
     */
    private function getTeam($name = 'Team name')
    {
        return new Team($name);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('Team name', $this->getTeam()->getName());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\EntityInterface', $this->getTeam());
    }

    public function testShouldBeTeam()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamInterface', $this->getTeam());
    }

    public function testShouldReturnTheArrayRepresentation()
    {
        $expected = array(
            'id'   => 'name',
            'name' => 'name',
        );

        $this->assertSame($expected, $this->getTeam('name')->toArray());
    }

    public function testShouldManageTeamMembers()
    {
        $team   = $this->getTeam();
        $member = $this->getMockMember();

        $this->assertEmpty($team->getMembers());
        $teamMember = $team->addMember($member);
        $this->assertCount(1, $team->getMembers());

        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamMember', $teamMember);
        $this->assertInstanceOf('Star\Component\Sprint\Entity\MemberInterface', $teamMember);
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamInterface', $teamMember);
        $this->assertSame($team, $teamMember->getTeam());
        $this->assertSame($member, $teamMember->getMember());

        $team->removeMember($member);
        $this->assertEmpty($team->getMembers());
    }
}
