<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class TeamDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\TeamData
 */
class TeamDataTest extends UnitTestCase
{
    /**
     * @param string $name
     *
     * @return TeamData
     */
    private function getTeam($name = 'Team name')
    {
        return new TeamData($name);
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
        $this->assertInstanceOfTeam($this->getTeam());
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
        $sprinter       = $this->getMockSprinter();
        $notFoundMember = $this->getMockSprinter();

        $this->assertEmpty($team->getMembers());
        $teamMember = $team->addMember($sprinter);

        $this->assertCount(1, $team->getMembers());
        $this->assertInstanceOfTeamMember($teamMember);
        $team->removeMember($notFoundMember);
        $this->assertCount(1, $team->getMembers());

        $team->removeMember($sprinter);
        $this->assertEmpty($team->getMembers());
    }
}