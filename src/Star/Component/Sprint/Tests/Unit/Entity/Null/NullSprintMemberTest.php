<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Null;

use Star\Component\Sprint\Entity\Null\NullSprintMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullSprintMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Null
 *
 * @covers Star\Component\Sprint\Entity\Null\NullSprintMember
 */
class NullSprintMemberTest extends UnitTestCase
{
    /**
     * @dataProvider provideDoNothingMethods
     */
    public function testShouldDoNothing($expected, $method)
    {
        $sprintMember = new NullSprintMember();
        $this->assertSame($expected, $sprintMember->{$method}());
    }

    public function provideDoNothingMethods()
    {
        return array(
            array(null, 'getId'),
            array(array(), 'toArray'),
            array(0, 'getAvailableManDays'),
            array(0, 'getActualVelocity'),
        );
    }

    public function testShouldReturnNullTeamMember()
    {
        $sprintMember = new NullSprintMember();
        $this->assertInstanceOfTeamMember($sprintMember->getTeamMember());
    }

    public function testShouldReturnNullSprint()
    {
        $sprintMember = new NullSprintMember();
        $this->assertInstanceOfSprint($sprintMember->getSprint());
    }
}
