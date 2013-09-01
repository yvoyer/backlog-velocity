<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;

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
        $commandName  = 'b:s:a';
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
}
