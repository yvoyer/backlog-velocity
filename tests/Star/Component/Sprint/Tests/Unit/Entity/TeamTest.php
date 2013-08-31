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
        $this->assertInstanceOfEntity($this->getTeam());
    }

    public function testShouldBeTeam()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamInterface', $this->getTeam());
    }

    public function testShouldReturnTheArrayRepresentation()
    {
        $expected = array(
            'id'   => null,
            'name' => 'name',
        );

        $this->assertSame($expected, $this->getTeam('name')->toArray());
    }

    public function testShouldManageTeamMembers()
    {
        $team           = $this->getTeam();
        $member         = $this->getMockMember();
        $notFoundMember = $this->getMockMember();

        $this->assertEmpty($team->getMembers());
        $teamMember = $team->addMember($member);

        $this->assertCount(1, $team->getMembers());
        $this->assertInstanceOf('Star\Component\Sprint\Entity\TeamMember', $teamMember);
        $team->removeMember($notFoundMember);
        $this->assertCount(1, $team->getMembers());

        $team->removeMember($member);
        $this->assertEmpty($team->getMembers());
    }
}
