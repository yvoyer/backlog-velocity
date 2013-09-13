<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprinter;

use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class JoinTeamCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Sprinter
 *
 * @covers Star\Component\Sprint\Command\Sprinter\JoinTeamCommand
 */
class JoinTeamCommandTest extends UnitTestCase
{
    /**
     * @return JoinTeamCommand
     */
    private function getCommand()
    {
        return new JoinTeamCommand($this->getMockSprinterRepository(), $this->getMockTeamRepository());
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), JoinTeamCommand::NAME, 'Link a sprinter to a team.');
    }

    public function testShouldHaveATeamOption()
    {
        $this->assertCommandHasOption($this->getCommand(), JoinTeamCommand::OPTION_TEAM);
    }

    public function testShouldHaveASprinterOption()
    {
        $this->assertCommandHasOption($this->getCommand(), JoinTeamCommand::OPTION_SPRINTER);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Sprinter name must be supplied
     */
    public function testShouldHaveTheSprinterOptionSupplied()
    {
        $command = $this->getCommand();
        $this->executeCommand($command);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function testShouldHaveTheTeamOptionSupplied()
    {
        $command = $this->getCommand();
        $this->executeCommand($command, array('--' . JoinTeamCommand::OPTION_SPRINTER => 'val'));
    }
}
