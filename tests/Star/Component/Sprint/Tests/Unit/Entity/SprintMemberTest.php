<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\SprintMember;
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
     * @param integer $availableManDays
     * @param integer $actualVelocity
     *
     * @return SprintMember
     */
    public function getTeamMember($availableManDays = null, $actualVelocity = null)
    {
        return new SprintMember($availableManDays, $actualVelocity);
    }

    public function testShouldHaveAvailableManDays()
    {
        $availableManDays = mt_rand();
        $this->assertSame($availableManDays, $this->getTeamMember($availableManDays)->getAvailableManDays());
    }

    public function testShouldHaveActualVelocity()
    {
        $actualVelocity = mt_rand();
        $this->assertSame($actualVelocity, $this->getTeamMember(null, $actualVelocity)->getActualVelocity());
    }
}
