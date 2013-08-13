<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Class AddCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\AddCommand
 */
class AddCommandTest extends UnitTestCase
{
    /**
     * @param TeamRepository $repository
     *
     * @return AddCommand
     */
    private function getCommand(TeamRepository $repository = null)
    {
        $repository = $this->getMockTeamRepository($repository);

        return new AddCommand($repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:team:add', $this->getCommand()->getName());
    }

    public function testShouldSaveTheInputNameInRepository()
    {
        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf('Star\Component\Sprint\Entity\Team'));

        $command = $this->getCommand($repository);

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
