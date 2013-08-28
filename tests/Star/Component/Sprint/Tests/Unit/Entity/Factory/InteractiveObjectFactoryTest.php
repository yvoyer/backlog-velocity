<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Factory;

use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \Symfony\Component\Console\Helper\DialogHelper    $dialog
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return InteractiveObjectFactory
     */
    private function getFactory(DialogHelper $dialog = null, OutputInterface $output = null)
    {
        $dialog = $this->getMockDialogHelper($dialog);
        $output = $this->getMockOutput($output);

        return new InteractiveObjectFactory($dialog, $output);
    }

    public function testShouldBeEntityCreator()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }

    public function testShouldCreateTheTeamBasedOnInfoFromUser()
    {
        $name   = uniqid('name');
        $output = $this->getMockOutput();
        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->with($output, '<question>Enter the team name: </question>')
            ->will($this->returnValue($name));

        $factory = $this->getFactory($dialog, $output);
        $this->assertInstanceOfTeam($factory->createTeam());
    }

    /**
     * @param DialogHelper $dialog
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockDialogHelper(DialogHelper $dialog = null)
    {
        return $this->getMockCustom('Symfony\Component\Console\Helper\DialogHelper', $dialog, false);
    }

    /**
     * @param OutputInterface $output
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockOutput(OutputInterface $output = null)
    {
        return $this->getMockCustom('Symfony\Component\Console\Output\OutputInterface', $output);
    }
}
