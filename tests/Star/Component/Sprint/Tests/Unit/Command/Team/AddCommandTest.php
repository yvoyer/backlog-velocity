<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\AddCommand
 */
class AddCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Execute a command to test.
     *
     * @param Command $command
     * @param array $input
     *
     * @return string
     */
    protected function executeCommand(Command $command, array $input = array())
    {
        $tester = new CommandTester($command);
        $tester->execute($input);

        return $tester->getDisplay();
    }

    /**
     * @param Repository $repository
     *
     * @return AddCommand
     */
    private function getCommand(Repository $repository = null)
    {
        if (null === $repository) {
            $repository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        }

        return new AddCommand($repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:team:add', $this->getCommand()->getName());
    }

    public function testShouldSaveTheInputNameInRepository()
    {
        $repository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        $repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf('Star\Component\Sprint\Team'));

        $command    = $this->getCommand($repository);

        $dialogHelper = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array(), array(), '', false);
        $dialogHelper
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->isInstanceOf('Symfony\Component\Console\Output\OutputInterface'),
                '<question>Enter the team name: </question>'
            )
            ->will($this->returnValue('name'));
        $helperSet = new HelperSet(array('dialog' => $dialogHelper));

        $command->setHelperSet($helperSet);

        $display = $this->executeCommand($command, array());
        $this->assertContains("Team 'name' was successfuly saved.", $display);
    }
}
