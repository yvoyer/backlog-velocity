<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Null;

use Star\Component\Sprint\Entity\Null\NullTeamMember;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullTeamMemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Null
 *
 * @covers Star\Component\Sprint\Entity\Null\NullTeamMember
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
        );
    }
}