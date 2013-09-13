<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity;

use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class MemberTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity
 *
 * @covers Star\Component\Sprint\Entity\Member
 */
class MemberTest extends UnitTestCase
{
    /**
     * @return Member
     */
    private function getMember()
    {
        return new Member();
    }

    public function testShouldBeAnEntity()
    {
        $this->assertInstanceOfEntity($this->getMember());
    }

    public function testShouldBeMember()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Entity\MemberInterface', $this->getMember());
    }
}
