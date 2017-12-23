<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\BacklogPlugin;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null\NullPlugin;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null\NullRepositoryManager;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null\NullTeamFactory;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class BacklogApplicationTest extends TestCase
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
            'backlog:project:create' => array('b:p:c'),
        );
    }

    public function testShouldBuildThePluginOnRegister()
    {
        $this->plugin = $this->createMock(BacklogPlugin::class);
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
