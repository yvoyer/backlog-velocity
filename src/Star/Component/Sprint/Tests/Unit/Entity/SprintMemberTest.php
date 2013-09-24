<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\SprintMember
 */
class SprintMemberTest extends UnitTestCase
{
    /**
     * @param integer                                  $availableManDays
     * @param integer                                  $actualVelocity
     * @param \Star\Component\Sprint\Entity\Sprint     $sprint
     * @param \Star\Component\Sprint\Entity\TeamMember $teamMember
     *
     * @return SprintMember
     */
    public function getSprintMember(
        $availableManDays = null,
        $actualVelocity = null,
        Sprint $sprint = null,
        TeamMember $teamMember = null
    ) {
        $sprint     = $this->getMockSprint($sprint);
        $teamMember = $this->getMockTeamMember($teamMember);

        return new SprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);
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
}
