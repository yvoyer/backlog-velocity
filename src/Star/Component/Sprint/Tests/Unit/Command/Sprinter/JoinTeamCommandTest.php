<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Sprinter;

use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
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
     * @param ObjectManager        $objectManager
     * @param TeamMemberRepository $teamMemberRepository
     *
     * @return JoinTeamCommand
     */
    private function getCommand(
        ObjectManager $objectManager = null,
        TeamMemberRepository $teamMemberRepository = null
    ) {
        $objectManager = $this->getMockObjectManager($objectManager);
        $teamMemberRepository = $this->getMockTeamMemberRepository($teamMemberRepository);

        return new JoinTeamCommand($objectManager, $teamMemberRepository);
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

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The team could not be found.
     */
    public function testShouldThrowExceptionWhenTeamNotFound()
    {
        $command = $this->getCommand();
        $inputs = array(
            '--' . JoinTeamCommand::OPTION_SPRINTER => 'sprinterName',
            '--' . JoinTeamCommand::OPTION_TEAM     => 'teamName',
        );
        $this->executeCommand($command, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The sprinter could not be found.
     */
    public function testShouldThrowExceptionWhenSprinterNotFound()
    {
        $objectManager = $this->getMockObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getTeam')
            ->with('teamName')
            ->will($this->returnValue($this->getMockTeam()));

        $command = $this->getCommand($objectManager);
        $inputs = array(
            '--' . JoinTeamCommand::OPTION_SPRINTER => 'sprinterName',
            '--' . JoinTeamCommand::OPTION_TEAM     => 'teamName',
        );
        $this->executeCommand($command, $inputs);
    }

    public function testShouldSaveUsingTheFoundTeamAndSprinter()
    {
        $sprinterName = 'sprinterName';
        $teamName     = 'teamName';

        $sprinter   = $this->getMockSprinter();
        $teamMember = $this->getMockTeamMember();

        $team = $this->getMockTeam();
        $team
            ->expects($this->once())
            ->method('addMember')
            ->with($sprinter)
            ->will($this->returnValue($teamMember));

        $objectManager = $this->getMockObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getSprinter')
            ->with($sprinterName)
            ->will($this->returnValue($sprinter));
        $objectManager
            ->expects($this->once())
            ->method('getTeam')
            ->with($teamName)
            ->will($this->returnValue($team));

        $teamMemberRepository = $this->getMockTeamMemberRepository();
        $teamMemberRepository
            ->expects($this->once())
            ->method('add')
            ->with($teamMember);
        $teamMemberRepository
            ->expects($this->once())
            ->method('save');

        $command = $this->getCommand($objectManager, $teamMemberRepository);
        $inputs = array(
            '--' . JoinTeamCommand::OPTION_SPRINTER => $sprinterName,
            '--' . JoinTeamCommand::OPTION_TEAM     => $teamName,
        );
        $this->executeCommand($command, $inputs);
    }
}
