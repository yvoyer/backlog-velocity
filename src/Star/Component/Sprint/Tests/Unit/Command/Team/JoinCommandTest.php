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
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

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
     * @var int
     */
    private $expectedManDays = 44;

    /**
     * @var JoinCommand
     */
    private $sut;

    /**
     * @var EntityFinder
     */
    private $finder;

    /**
     * @var TeamMemberRepository
     */
    private $creator;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Sprinter
     */
    private $sprinter;

    /**
     * @var TeamMember
     */
    private $teamMember;

    /**
     * @var Team
     */
    private $team;

    public function setUp()
    {
        $this->sprinter   = $this->getMockSprinter();
        $this->teamMember = $this->getMockTeamMember();
        $this->team       = $this->getMockTeam();

        $this->finder     = $this->getMockEntityFinder();
        $this->creator    = $this->getMockEntityCreator();
        $this->repository = $this->getMockRepository();

        $this->sut = new JoinCommand($this->creator, $this->finder, $this->repository);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->sut, JoinCommand::NAME, 'Link a sprinter to a team.');
    }

    public function testShouldHaveATeamOption()
    {
        $this->assertCommandHasOption($this->sut, JoinCommand::OPTION_TEAM);
    }

    public function testShouldHaveASprinterOption()
    {
        $this->assertCommandHasOption($this->sut, JoinCommand::OPTION_SPRINTER);
    }

    public function testShouldHaveAnAvailableManDaysOption()
    {
        $this->assertCommandHasOption($this->sut, 'man-days', -1);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Sprinter name must be supplied
     */
    public function testShouldHaveTheSprinterOptionSupplied()
    {
        $this->executeCommand($this->sut);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function testShouldHaveTheTeamOptionSupplied()
    {
        $this->executeCommand($this->sut, array('--sprinter' => 'val'));
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The team could not be found.
     */
    public function testShouldThrowExceptionWhenTeamNotFound()
    {
        $inputs = array(
            '--sprinter' => 'sprinterName',
            '--team'     => 'teamName',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The sprinter could not be found.
     */
    public function testShouldThrowExceptionWhenSprinterNotFound()
    {
        $this->assertTeamIsFound();
        $inputs = array(
            '--sprinter' => 'sprinterName',
            '--team'     => 'teamName',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    public function testShouldSaveUsingTheFoundTeamAndSprinter()
    {
        $this->assertMemberIsAddedToTeam();
        $this->assertTeamIsFound();
        $this->assertSprinterIsFound();
        $this->assertTeamMemberIsSaved($this->teamMember);

        $inputs = array(
            '--sprinter' => 'sprinterName',
            '--team'     => 'teamName',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    /**
     * @param $teamMember
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|TeamMemberRepository
     */
    private function assertTeamMemberIsSaved($teamMember)
    {
        $this->repository
            ->expects($this->at(0))
            ->method('add')
            ->with($this->team);
        $this->repository
            ->expects($this->at(1))
            ->method('add')
            ->with($teamMember);
        $this->repository
            ->expects($this->at(2))
            ->method('add')
            ->with($this->sprinter);
        $this->repository
            ->expects($this->once())
            ->method('save');
    }

    private function assertMemberIsAddedToTeam()
    {
        $this->team
            ->expects($this->once())
            ->method('addMember')
            ->with($this->sprinter)
            ->will($this->returnValue($this->teamMember, $this->expectedManDays));
    }

    private function assertTeamIsFound()
    {
        $this->finder
            ->expects($this->once())
            ->method('findTeam')
            ->with('teamName')
            ->will($this->returnValue($this->team));
    }

    private function assertSprinterIsFound()
    {
        $this->finder
            ->expects($this->once())
            ->method('findSprinter')
            ->with('sprinterName')
            ->will($this->returnValue($this->sprinter));
    }
}
