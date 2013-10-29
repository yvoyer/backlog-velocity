<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\JoinCommand;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class JoinTeamCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\JoinCommand
 */
class JoinTeamCommandTest extends UnitTestCase
{
    /**
     * @param EntityFinder         $finder
     * @param TeamMemberRepository $teamMemberRepository
     *
     * @internal param \Star\Component\Sprint\Entity\Query\EntityFinder $objectManager
     * @return JoinCommand
     */
    private function getCommand(
        EntityFinder $finder = null,
        TeamMemberRepository $teamMemberRepository = null
    ) {
        $finder = $this->getMockEntityFinder($finder);
        $teamMemberRepository = $this->getMockTeamMemberRepository($teamMemberRepository);

        return new JoinCommand($finder, $teamMemberRepository);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->getCommand(), JoinCommand::NAME, 'Link a sprinter to a team.');
    }

    public function testShouldHaveATeamOption()
    {
        $this->assertCommandHasOption($this->getCommand(), JoinCommand::OPTION_TEAM);
    }

    public function testShouldHaveASprinterOption()
    {
        $this->assertCommandHasOption($this->getCommand(), JoinCommand::OPTION_SPRINTER);
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
        $this->executeCommand($command, array('--' . JoinCommand::OPTION_SPRINTER => 'val'));
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The team could not be found.
     */
    public function testShouldThrowExceptionWhenTeamNotFound()
    {
        $command = $this->getCommand();
        $inputs = array(
            '--' . JoinCommand::OPTION_SPRINTER => 'sprinterName',
            '--' . JoinCommand::OPTION_TEAM     => 'teamName',
        );
        $this->executeCommand($command, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The sprinter could not be found.
     */
    public function testShouldThrowExceptionWhenSprinterNotFound()
    {
        $finder = $this->getMockEntityFinder();
        $finder
            ->expects($this->once())
            ->method('findTeam')
            ->with('teamName')
            ->will($this->returnValue($this->getMockTeam()));

        $command = $this->getCommand($finder);
        $inputs = array(
            '--' . JoinCommand::OPTION_SPRINTER => 'sprinterName',
            '--' . JoinCommand::OPTION_TEAM     => 'teamName',
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

        $finder = $this->getMockEntityFinder();
        $finder
            ->expects($this->once())
            ->method('findSprinter')
            ->with($sprinterName)
            ->will($this->returnValue($sprinter));
        $finder
            ->expects($this->once())
            ->method('findTeam')
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

        $command = $this->getCommand($finder, $teamMemberRepository);
        $inputs = array(
            '--' . JoinCommand::OPTION_SPRINTER => $sprinterName,
            '--' . JoinCommand::OPTION_TEAM     => $teamName,
        );
        $this->executeCommand($command, $inputs);
    }
}
