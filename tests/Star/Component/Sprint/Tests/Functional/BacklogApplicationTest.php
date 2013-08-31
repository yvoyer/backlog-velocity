<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * Class BacklogApplicationTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 *
 * @covers Star\Component\Sprint\BacklogApplication
 */
class BacklogApplicationTest extends UnitTestCase
{
    /**
     * @var BacklogApplication
     */
    private $application;

    public function setUp()
    {
        $isDevMode = true;
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
        $config = Setup::createXMLMetadataConfiguration(array($root . '/config/doctrine'), $isDevMode);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $this->application = new BacklogApplication($conn, $config);
        $this->application->setAutoExit(false);

        $tester = $this->getApplicationTester($this->application);
        // Automatic schema creation
        $tester->run(array('o:s:c'));
    }

    /**
     * @param Application $application
     *
     * @return ApplicationTester
     */
    private function getApplicationTester(Application $application)
    {
        return new ApplicationTester($application);
    }

    /**
     * @param Application $application
     * @param string      $commandName
     * @param string      $will
     * @param string      $method
     */
    private function setDialog(Application $application, $commandName, $will, $method = 'ask')
    {
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper');
        $dialog
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue($will));

        // We override the standard helper with our mock
        $command = $application->find($commandName);
        $command->getHelperSet()->set($dialog, 'dialog');
    }

    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldAddAllTeams(array $teams)
    {
        $commandName = 'b:t:a';
        $teamName    = $teams['name'];

        $this->setDialog($this->application, $commandName, $teamName);
        $tester = $this->getApplicationTester($this->application);
        $tester->run(array($commandName));

        $em = $this->application->getEntityManager();

        $teams = $em->getRepository(Team::LONG_NAME)->findAll();
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

        $commandName = 'b:t:l';
        $tester = $this->getApplicationTester($this->application);
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
     * Creates a Team.
     *
     * @param string $name
     *
     * @return Team
     */
    private function createTeam($name)
    {
        $team = new Team($name);

        $em = $this->application->getEntityManager();
        $em->persist($team);
        $em->flush();

        return $team;
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

        $em                 = $this->application->getEntityManager();
        $sprinterRepository = $em->getRepository(Sprinter::LONG_NAME);
        $this->assertEmpty($sprinterRepository->findAll());

        $this->setDialog($this->application, $commandName, $sprinterName);
        $tester = $this->getApplicationTester($this->application);
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
