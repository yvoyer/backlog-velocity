<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Tests\Entity;

use Star\Plugin\Null\Entity\NullTeamMember;
use tests\UnitTestCase;

/**
 * Class NullTeamMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Tests\Entity
 *
 * @covers Star\Plugin\Null\Entity\NullTeamMember
 */
class NullTeamMemberTest extends UnitTestCase
{
    /**
     * @dataProvider provideDoNothingMethods
     */
    public function testShouldDoNothing($expected, $method)
    {
        $sprint = new NullTeamMember();
        $this->assertSame($expected, $sprint->{$method}());
    }

    public function provideDoNothingMethods()
    {
        return array(
            array(null, 'getId'),
            array(array(), 'toArray'),
            array(false, 'isValid'),
            array(0, 'getAvailableManDays'),
        );
    }

    public function testShouldReturnNullMember()
    {
        $teamMember = new NullTeamMember();
        $this->assertInstanceOfPerson($teamMember->getPerson());
    }

    public function testShouldReturnNullTeam()
    {
        $teamMember = new NullTeamMember();
        $this->assertInstanceOfTeam($teamMember->getTeam());
    }
}
