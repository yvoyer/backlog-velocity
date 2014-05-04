<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\JoinCommand;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\MemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
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
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var MemberRepository
     */
    private $personRepository;

    /**
     * @var TeamMemberRepository
     */
    private $teamMemberRepository;

    /**
     * @var Person
     */
    private $person;

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
        $this->person = $this->getMockPerson();
        $this->teamMember = $this->getMockTeamMember();
        $this->team = $this->getMockTeam();

        $this->teamRepository = $this->getMockTeamRepository();
        $this->personRepository = $this->getMockMemberRepository();
        $this->teamMemberRepository = $this->getMockTeamMemberRepository();

        $this->sut = new JoinCommand($this->teamRepository, $this->personRepository, $this->teamMemberRepository);
    }

    public function testShouldBeACommand()
    {
        $this->assertInstanceOfCommand($this->sut, JoinCommand::NAME, 'Link a person to a team.');
    }

    public function testShouldHaveATeamArgument()
    {
        $this->assertCommandHasArgument($this->sut, JoinCommand::ARGUMENT_TEAM, null, true);
    }

    public function testShouldHaveASprinterArgument()
    {
        $this->assertCommandHasArgument($this->sut, JoinCommand::ARGUMENT_PERSON, null, true);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Sprinter name must be supplied
     */
    public function testShouldThrowExceptionWhenSprinterEmpty()
    {
        $inputs = array(
            'sprinter' => '',
            'team'     => '',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Team name must be supplied
     */
    public function testShouldThrowExceptionWhenTeamEmpty()
    {
        $inputs = array(
            'sprinter' => 'val',
            'team'     => '',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The team could not be found.
     */
    public function testShouldThrowExceptionWhenTeamNotFound()
    {
        $inputs = array(
            'sprinter' => 'sprinterName',
            'team'     => 'teamName',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage The person could not be found.
     */
    public function testShouldThrowExceptionWhenPersonNotFound()
    {
        $this->assertTeamIsFound();
        $inputs = array(
            'sprinter' => 'sprinterName',
            'team'     => 'teamName',
        );
        $this->executeCommand($this->sut, $inputs);
    }

    public function testShouldSaveUsingTheFoundTeamAndSprinter()
    {
        $this->assertMemberIsAddedToTeam();
        $this->assertTeamIsFound();
        $this->assertPersonIsFound();
        $this->assertTeamMemberIsSaved($this->teamMember);

        $inputs = array(
            'sprinter' => 'sprinterName',
            'team'     => 'teamName',
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
        $this->teamMemberRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->teamMember);
        $this->teamMemberRepository
            ->expects($this->once())
            ->method('save');
    }

    private function assertMemberIsAddedToTeam()
    {
        $this->team
            ->expects($this->once())
            ->method('addMember')
            ->with($this->person)
            ->will($this->returnValue($this->teamMember, $this->expectedManDays));
    }

    private function assertTeamIsFound()
    {
        $this->teamRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('teamName')
            ->will($this->returnValue($this->team));
    }

    private function assertPersonIsFound()
    {
        $this->personRepository
            ->expects($this->once())
            ->method('find')
            ->with('sprinterName')
            ->will($this->returnValue($this->person));
    }
}
