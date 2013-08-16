<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\BacklogApplication;
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
     * @var string The base data folder.
     */
    private static $baseFolder;

    public static function setUpBeforeClass()
    {
        self::$baseFolder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test';
        $pattern = implode(DIRECTORY_SEPARATOR, array(self::$baseFolder, '*.yml'));
        $files   = glob($pattern);

        if (false === empty($files)) {
            array_map('unlink', $files);
        }

        if (false === file_exists(self::$baseFolder)) {
            mkdir(self::$baseFolder);
        }
    }

    /**
     * @return BacklogApplication
     */
    private function getApplication()
    {
        $application = new BacklogApplication(self::$baseFolder);
        $application->setAutoExit(false);

        return $application;
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
     * Returns the content of $file.
     *
     * @param string $file
     *
     * @return string
     */
    private function getFileContent($file)
    {
        $filePath = self::$baseFolder . DIRECTORY_SEPARATOR . $file;
        $this->assertTrue(file_exists($filePath), "The file {$filePath} should exists.");

        return file_get_contents($filePath);
    }

    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldAddAllTeams($teamName, array $expectedTeams)
    {
        $commandName = 'b:t:a';
        $application = $this->getApplication();
        $this->setDialog($application, $commandName, $teamName);
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));

        $content = $this->getFileContent('teams.yml');
        foreach ($expectedTeams as $expectedTeam) {
            $this->assertContains($expectedTeam, $content);
        }
    }

    /**
     * @dataProvider provideNamesForTeams
     */
    public function testShouldListAllTeams($teamName)
    {
        $commandName = 'b:t:l';
        $application = $this->getApplication();
        $tester = $this->getApplicationTester($application);
        $tester->run(array($commandName));
        $display = $tester->getDisplay();

        $this->assertContains($teamName, $display);
    }

    public function provideNamesForTeams()
    {
        $empire = 'The Galactic Empire';
        $rebel  = 'The Rebel Alliance';
        $crime  = 'The Crime Syndicate';
        $siths  = 'The Siths';

        return array(
            array($empire, array($empire)),
            array($rebel, array($empire, $rebel)),
            array($crime, array($empire, $rebel, $crime)),
            array($siths, array($empire, $rebel, $crime, $siths)),
        );
    }
}
