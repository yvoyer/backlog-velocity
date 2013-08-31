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
     * @return InteractiveObjectFactory
     */
    private function getFactory()
    {
        return new InteractiveObjectFactory();
    }

    public function testShouldBeEntityCreator()
    {
        $this->assertInstanceOfEntityCreator($this->getFactory());
    }

    public function testShouldConfigureTheConsoleDependencies()
    {
        $factory = $this->getFactory();
        $this->assertAttributeInstanceOf('Star\Component\Sprint\Null\NullDialog', 'dialog', $factory);
        $this->assertAttributeInstanceOf('Symfony\Component\Console\Output\NullOutput', 'output', $factory);

        $dialog = $this->getMockDialogHelper();
        $output = $this->getMockOutput();
        $factory->setup($dialog, $output);
        $this->assertAttributeSame($dialog, 'dialog', $factory);
        $this->assertAttributeSame($output, 'output', $factory);
    }

    /**
     * @depends testShouldConfigureTheConsoleDependencies
     */
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

        $factory = $this->getFactory();
        $factory->setup($dialog, $output);
        $this->assertInstanceOfTeam($factory->createTeam());
    }

    /**
     * @depends testShouldConfigureTheConsoleDependencies
     */
    public function testShouldCreateTheSprintBasedOnInfoFromUser()
    {
        $name   = uniqid('name');
        $output = $this->getMockOutput();
        $dialog = $this->getMockDialogHelper();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->with($output, '<question>Enter the sprint name: </question>')
            ->will($this->returnValue($name));

        $factory = $this->getFactory();
        $factory->setup($dialog, $output);
        $this->assertInstanceOfSprint($factory->createSprint());
    }

    /**
     * @depends testShouldConfigureTheConsoleDependencies
     */
    public function testShouldCreateTheMemberBasedOnInfoFromUser()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOfMember($factory->createMember());
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