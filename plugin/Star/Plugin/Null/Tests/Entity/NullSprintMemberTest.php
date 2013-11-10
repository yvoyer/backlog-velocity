<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Tests\Entity;

use Star\Plugin\Null\Entity\NullSprintMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullSprintMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Tests\Entity
 *
 * @covers Star\Plugin\Null\Entity\NullSprintMember
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
            array(false, 'isValid'),
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
