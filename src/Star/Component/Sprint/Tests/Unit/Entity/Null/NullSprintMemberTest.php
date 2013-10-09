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
        $sprint = new NullSprintMember();
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
