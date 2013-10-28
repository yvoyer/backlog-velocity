<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockDialog()
    {
        return $this->getMockCustom('Symfony\Component\Console\Helper\DialogHelper', null, false);
    }

    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldAddAllTeams(array $teams)
    {
        $commandName = 'b:t:a';
        $teamName    = $teams['name'];

        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->will($this->returnValue($teamName));

        $application = $this->getApplication($dialog);

        $teamRepository = $this->getTeamRepository();
        $this->assertEmpty($teamRepository->findAll());

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
        $application = $this->getApplication();
        $teamName    = $teams['name'];
        $this->generateTeam($teamName);

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

        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->will($this->returnValue($sprinterName));

        $application  = $this->getApplication($dialog);

        $sprinterRepository = $this->getSprinterRepository();
        $this->assertEmpty($sprinterRepository->findAll());

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
        $application  = $this->getApplication();
        $teamName     = uniqid('team-name');
        $team         = $this->generateTeam($teamName);
        $sprinterName = uniqid('sprinter-name');
        $this->generateSprinter($sprinterName);

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

        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('ask')
            ->will($this->returnValue($sprintName));

        $application = $this->getApplication($dialog);

        $sprintRepository = $this->getSprintRepository();
        $this->assertEmpty($sprintRepository->findAll());

        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName, '--name' => $sprintName));

        $sprints = $sprintRepository->findAll();
        $this->assertCount(1, $sprints);
    }

    public function testShouldUpdateSprint()
    {
        $searchName = 'My Sprint';
        $newName    = 'My new name';

        $tester = $this->getApplicationTester($this->getApplication());
        $sprint = $this->generateSprint($searchName);
        $this->assertCount(1, $this->getSprintRepository()->findAll());

        $tester->run(array('b:sprint:u', '--search' => $searchName, '--name' => $newName));

        $this->assertCount(1, $this->getSprintRepository()->findAll());
        $this->assertSame($newName, $this->getRefreshedObject($sprint)->getName());
    }

    public function testShouldListSprints()
    {
        $tester = $this->getApplicationTester($this->getApplication());
        $this->generateSprint('sprint1');
        $this->generateSprint('sprint2');
        $this->assertCount(2, $this->getSprintRepository()->findAll());

        $tester->run(array('b:sprint:l'));

        $this->assertContains('sprint1', $tester->getDisplay());
        $this->assertContains('sprint2', $tester->getDisplay());
    }

    /**
     * @dataProvider provideSupportedCommandData
     *
     * @param $commandName
     */
    public function testShouldHaveTheCommand($commandName)
    {
        $this->assertTrue($this->getApplication()->has($commandName), 'The command should be registered');
    }

    public function provideSupportedCommandData()
    {
        return array(
            array('backlog:sprint:add'),
            array('backlog:sprint:list'),
            // @todo array('backlog:sprint:join'),
            array('backlog:sprint:update'),
            array('backlog:sprinter:add'),
            array('backlog:sprinter:join-team'),
            array('backlog:team:add'),
            array('backlog:team:list'),
        );
    }
}
