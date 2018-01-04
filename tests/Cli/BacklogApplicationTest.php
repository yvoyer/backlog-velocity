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
use Symfony\Component\Console\Command\Command;

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
        $this->application = new BacklogApplication();
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
            Command::class,
            $this->application->find($name),
            "The command {$name} should be registered"
        );
    }

    public function provideRegisteredCommandName()
    {
        return [
            'help' => ['help'],
            'list' => ['list'],
            'run' => ['run'],

            'backlog:sprint:add' => ['b:s:a'],
            'backlog:sprint:list' => ['b:s:l'],
            'backlog:sprint:join' => ['b:s:j'],
            'backlog:sprint:start' => ['b:s:s'],
            'backlog:sprint:close' => ['b:s:c'],

            'backlog:team:add' => ['b:t:a'],
            'backlog:team:join' => ['b:t:j'],
            'backlog:team:list' => ['b:t:l'],

            'backlog:person:add' => ['b:p:a'],
            'backlog:person:list' => ['b:p:l'],
            'backlog:project:create' => ['b:p:c'],
        ];
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

        $this->application->registerPlugin($this->plugin);
    }
}
