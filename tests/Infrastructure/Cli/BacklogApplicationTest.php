<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Plugin\Null\NullTeamFactory;
use Star\Plugin\Null\NullPlugin;
use Star\Plugin\Null\NullRepositoryManager;
use Symfony\Component\Console\Command\Command;
use tests\UnitTestCase;

/**
 * Class BacklogApplicationTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\BacklogApplication
 * @uses Star\Component\Sprint\Command\Person\AddPersonCommand
 * @uses Star\Component\Sprint\Command\Person\ListPersonCommand
 * @uses Star\Component\Sprint\Command\Sprint\AddCommand
 * @uses Star\Component\Sprint\Command\Sprint\JoinSprintCommand
 * @uses Star\Component\Sprint\Command\Sprint\ListCommand
 * @uses Star\Component\Sprint\Command\Sprint\StartSprintCommand
 * @uses Star\Component\Sprint\Command\Sprint\CloseSprintCommand
 * @uses Star\Component\Sprint\Command\Team\AddCommand
 * @uses Star\Component\Sprint\Command\Team\JoinCommand
 * @uses Star\Component\Sprint\Command\Team\ListCommand
 * @uses Star\Component\Sprint\Command\RunCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 * @uses Star\Plugin\Null\NullPlugin
 * @uses Star\Plugin\Null\NullRepositoryManager
 * @uses Star\Plugin\Null\NullTeamFactory
 * @uses Star\Plugin\Null\Entity\NullTeam
 * @uses Star\Plugin\Null\Repository\NullPersonRepository
 * @uses Star\Plugin\Null\Repository\NullSprintRepository
 * @uses Star\Plugin\Null\Repository\NullTeamRepository
 * @uses Star\Plugin\Null\Repository\NullTeamMemberRepository
 */
class BacklogApplicationTest extends UnitTestCase
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var BacklogPlugin|\PHPUnit_Framework_MockObject_MockObject
     */
    private $plugin;

    public function setUp()
    {
        $this->application = new BacklogApplication('path');
        $this->application->registerPlugin(new NullPlugin());
        $this->application->setAutoExit(false);
    }

    public function testShouldHaveTheNumberOfCommand()
    {
        $this->assertCount(count($this->provideRegisteredCommandName()), $this->application->all(), 'There are non-registered expected commands.');
    }

    /**
     * @dataProvider provideRegisteredCommandName
     *
     * @param $name
     */
    public function testShouldHaveCommand($name)
    {
        $this->assertInstanceOf(
            'Symfony\Component\Console\Command\Command',
            $this->application->find($name),
            "The command {$name} should be registered"
        );
    }

    public function provideRegisteredCommandName()
    {
        return array(
            'help' => array('help'),
            'list' => array('list'),
            'run' => array('run'),

            'backlog:sprint:add' => array('b:s:a'),
            'backlog:sprint:list' => array('b:s:l'),
            'backlog:sprint:join' => array('b:s:j'),
            'backlog:sprint:start' => array('b:s:s'),
            'backlog:sprint:close' => array('b:s:c'),

            'backlog:team:add' => array('b:t:a'),
            'backlog:team:join' => array('b:t:j'),
            'backlog:team:list' => array('b:t:l'),

            'backlog:person:add' => array('b:p:a'),
            'backlog:person:list' => array('b:p:l'),
        );
    }

    public function testShouldBuildThePluginOnRegister()
    {
        $this->plugin = $this->getMockBacklogPlugin();
        $this->plugin
            ->expects($this->once())
            ->method('build')
            ->with($this->application);
        $this->plugin
            ->expects($this->once())
            ->method('getRepositoryManager')
            ->will($this->returnValue(new NullRepositoryManager()));
        $this->plugin
            ->expects($this->once())
            ->method('getTeamFactory')
            ->will($this->returnValue(new NullTeamFactory()));

        $this->application->registerPlugin($this->plugin);
    }

    public function testShouldReturnTheDefaultConfiguration()
    {
        $this->assertSame(array(), $this->application->getConfiguration());
    }

    public function testShouldReturnTheConfiguration()
    {
        $conf = array('something');
        $this->application = new BacklogApplication('', '', $conf);
        $this->assertSame($conf, $this->application->getConfiguration());
    }

    public function testShouldReturnTheEnvironment()
    {
        $this->application = new BacklogApplication('', 'test');
        $this->assertSame('test', $this->application->getEnvironment());
    }

    public function testShouldReturnTheEnvironmentByDefault()
    {
        $this->assertSame('dev', $this->application->getEnvironment());
    }

    public function testShouldReturnTheRootForTheApplication()
    {
        $this->assertSame('path', $this->application->getRootPath());
    }
}
