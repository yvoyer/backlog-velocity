<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Entity\Team;

/**
 * Class BacklogApplicationTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 *
 * @covers Star\Component\Sprint\BacklogApplication
 */
class BacklogApplicationTest extends FunctionalTestCase
{
    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldAddAllTeams(array $teams)
    {
        $commandName = 'b:t:a';
        $teamName    = $teams['name'];
        $application = $this->getApplication();

        $teamRepository = $this->getTeamRepository();
        $this->assertEmpty($teamRepository->findAll());

        $this->setDialog($application, $commandName, $teamName);
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));

        $teams = $teamRepository->findAll();
        $this->assertCount(1, $teams);
        $this->assertSame($teamName, $teams[0]->getName());
    }

    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldListAllTeams(array $teams)
    {
        $teamName = $teams['name'];
        $this->createTeam($teamName);
        $application = $this->getApplication();

        $commandName = 'b:t:l';
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));
        $display = $tester->getDisplay();

        $this->assertContains($teamName, $display);
    }

    /**
     * Provides the data about the teams.
     *
     * @return array
     */
    public function provideNamesForTeams()
    {
        return array($this->getFixture('teams.yml'));
    }

    /**
     * @dataProvider provideSprintersInformation
     *
     * @param array $sprinters
     */
    public function testShouldAddTheSprinters(array $sprinters)
    {
        $commandName  = 'b:sprinter:a';
        $sprinterName = $sprinters['name'];
        $application  = $this->getApplication();

        $sprinterRepository = $this->getSprinterRepository();
        $this->assertEmpty($sprinterRepository->findAll());

        $this->setDialog($application, $commandName, $sprinterName);
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));

        $sprinters = $sprinterRepository->findAll();
        $this->assertCount(1, $sprinters);
        $this->assertSame($sprinterName, $sprinters[0]->getName());
    }

    /**
     * Provides the data about the sprinters.
     *
     * @return array
     */
    public function provideSprintersInformation()
    {
        return array($this->getFixture('members.yml'));
    }

    /**
     * @covers Star\Component\Sprint\Command\Sprinter\JoinTeamCommand::execute
     */
    public function testShouldAddExistingSprinterWithTeam()
    {
        $teamName     = uniqid('team-name');
        $team         = $this->createTeam($teamName);
        $sprinterName = uniqid('sprinter-name');
        $sprinter     = $this->createSprinter($sprinterName);
        $application  = $this->getApplication();

        $this->assertCount(0, $team->getMembers());

        $tester = $this->getApplicationTester($application);
        $arguments = array(
            JoinTeamCommand::NAME,
            '--' . JoinTeamCommand::OPTION_TEAM     => $teamName,
            '--' . JoinTeamCommand::OPTION_SPRINTER => $sprinterName,
        );
        $tester->run($arguments);
        $this->assertContains("Sprinter '{$sprinterName}' is now part of team '{$teamName}'.", $tester->getDisplay());

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);
        $this->assertCount(1, $team->getMembers());
    }

    public function testShouldAddSprint()
    {
        $commandName = 'b:sprint:a';
        $sprintName  = uniqid('sprintName');
        $application = $this->getApplication();

        $sprintRepository = $this->getSprintRepository();
        $this->assertEmpty($sprintRepository->findAll());

        $this->setDialog($application, $commandName, $sprintName);
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));

        $sprints = $sprintRepository->findAll();
        $this->assertCount(1, $sprints);
        $this->assertSame($sprintName, $sprints[0]->getName());
    }
}
