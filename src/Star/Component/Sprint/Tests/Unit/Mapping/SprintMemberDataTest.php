<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Mapping;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintMemberDataTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Mapping
 *
 * @covers Star\Component\Sprint\Mapping\SprintMemberData
 */
class SprintMemberDataTest extends UnitTestCase
{
    /**
     * @param integer    $availableManDays
     * @param integer    $actualVelocity
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMemberData
     */
    public function getSprintMember(
        $availableManDays = null,
        $actualVelocity = null,
        Sprint $sprint = null,
        TeamMember $teamMember = null
    ) {
        $sprint     = $this->getMockSprint($sprint);
        $teamMember = $this->getMockTeamMember($teamMember);

        return new SprintMemberData($availableManDays, $actualVelocity, $sprint, $teamMember);
    }

    public function testShouldHaveAvailableManDays()
    {
        $availableManDays = mt_rand();
        $this->assertSame($availableManDays, $this->getSprintMember($availableManDays)->getAvailableManDays());
    }

    public function testShouldHaveActualVelocity()
    {
        $actualVelocity = mt_rand();
        $this->assertSame($actualVelocity, $this->getSprintMember(null, $actualVelocity)->getActualVelocity());
    }

    public function testShouldBeEntity()
    {
        $this->assertInstanceOfEntity($this->getSprintMember());
    }

    public function testShouldBeATeamMember()
    {
        $this->assertInstanceOfSprintMember($this->getSprintMember());
    }

    public function testShouldReturnTheSprint()
    {
        $sprint = $this->getMockSprint();
        $this->assertSame($sprint, $this->getSprintMember(null, null, $sprint)->getSprint());
    }

    public function testShouldReturnTheTeamMember()
    {
        $member = $this->getMockTeamMember();
        $this->assertSame($member, $this->getSprintMember(null, null, null, $member)->getTeamMember());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testShouldBeValid()
    {
        // @todo Implement correclty
        $this->getSprintMember()->isValid();
    }
}
