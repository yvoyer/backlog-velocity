<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class InteractiveObjectFactoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Factory
 *
 * @covers Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory
 */
class InteractiveObjectFactoryTest extends UnitTestCase
{
    /**
     * @return InteractiveObjectFactory
     */
    private function getFactory()
    {
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array(), array(), '', false);
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        return new InteractiveObjectFactory($dialog, $output);
    }

    public function testShouldBeEntityCreator()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }
}
