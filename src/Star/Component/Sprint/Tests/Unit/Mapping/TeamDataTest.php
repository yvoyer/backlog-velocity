<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Mapping\TeamData;

/**
 * Class TeamDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\TeamData
 */
class TeamDataTest extends AbstractValueProvider
{
    /**
     * @var TeamData
     */
    private $sut;

    public function setUp()
    {
        $this->sut = $this->getTeam();
    }

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
        $this->assertSame('Team name', $this->sut->getName());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->sut);
    }

    public function testShouldBeTeam()
    {
        $this->assertInstanceOfTeam($this->sut);
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
        $sprinter       = $this->getMockSprinter();
        $notFoundMember = $this->getMockSprinter();

        $this->assertEmpty($this->sut->getMembers());
        $teamMember = $this->sut->addMember($sprinter, 0);

        $this->assertCount(1, $this->sut->getMembers());
        $this->assertInstanceOfTeamMember($teamMember);
        $this->sut->removeMember($notFoundMember);
        $this->assertCount(1, $this->sut->getMembers());

        $this->sut->removeMember($sprinter);
        $this->assertEmpty($this->sut->getMembers());
    }

    /**
     * @dataProvider providerValidNames
     *
     * @param $name
     */
    public function testShouldBeValid($name)
    {
        $this->assertTrue($this->getTeam($name)->isValid());
    }

    /**
     * @dataProvider providerInvalidNames
     *
     * @param $name
     */
    public function testShouldNotBeValid($name)
    {
        $this->assertFalse($this->getTeam($name)->isValid());
    }

    public function testShouldReturnTheTeamAvailableManDays()
    {
        $member1 = $this->getMockSprinter();
        $member2 = $this->getMockSprinter();

        $this->sut->addMember($member1, 3);
        $this->sut->addMember($member2, 5);
        $this->assertSame(8, $this->sut->getAvailableManDays());
    }
}
