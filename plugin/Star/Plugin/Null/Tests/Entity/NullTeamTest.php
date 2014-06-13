<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Tests\Entity;

use Star\Plugin\Null\Entity\NullTeam;
use tests\UnitTestCase;

/**
 * Class NullTeamTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Tests\Entity
 *
 * @covers Star\Plugin\Null\Entity\NullTeam
 */
class NullTeamTest extends UnitTestCase
{
    /**
     * @dataProvider provideDoNothingMethods
     */
    public function testShouldDoNothing($expected, $method, $arg = null)
    {
        $sprint = new NullTeam();
        $this->assertSame($expected, $sprint->{$method}($arg));
    }

    public function provideDoNothingMethods()
    {
        return array(
            array('', 'getName'),
            array(null, 'getId'),
            array(array(), 'getTeamMembers'),
            array(array(), 'getClosedSprints'),
        );
    }

    public function testShouldReturnNullTeam()
    {
        $team = new NullTeam();
        $this->assertInstanceOfTeamMember($team->addTeamMember($this->getMockPerson()));
    }
}
