<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Null;

use Star\Component\Sprint\Entity\Null\NullTeam;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullTeamTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Null
 *
 * @covers Star\Component\Sprint\Entity\Null\NullTeam
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
            array(null, 'addMember', $this->getMockSprinter()),
            array(null, 'getId'),
            array(null, 'toArray'),
        );
    }
}
