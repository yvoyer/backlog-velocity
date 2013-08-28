<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DefaultObjectFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Factory
 *
 * @covers Star\Component\Sprint\Entity\Factory\DefaultObjectFactory
 */
class DefaultObjectFactoryTest extends UnitTestCase
{
    /**
     * @return DefaultObjectFactory
     */
    private function getFactory()
    {
        return new DefaultObjectFactory();
    }

    public function testShouldCreateTeam()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfTeam($factory->createTeam());
    }

    public function testShouldCreateSprint()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfSprint($factory->createSprint());
    }

    public function testShouldCreateMember()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfMember($factory->createMember());
    }
}
