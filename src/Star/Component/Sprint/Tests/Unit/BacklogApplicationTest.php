<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Tests\Stub\NullPlugin;
use Star\Component\Sprint\Tests\Stub\NullEntityCreator;
use Star\Component\Sprint\Tests\Stub\NullEntityFinder;
use Star\Component\Sprint\Tests\Stub\NullObjectManager;
use Star\Component\Sprint\Tests\Stub\NullRepositoryManager;

/**
 * Class BacklogApplicationTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit
 *
 * @covers Star\Component\Sprint\BacklogApplication
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
        $this->application = new BacklogApplication(array());
        $this->application->registerPlugin(new NullPlugin());
        $this->application->setAutoExit(false);
    }

    public function testShouldHaveTheNumberOfCommand()
    {
        $this->assertCount(8, $this->application->all(), 'There are non-registered expected commands.');
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
            'backlog:sprint:add'    => array('b:s:a'),
            'backlog:sprint:list'   => array('b:s:l'),
            'backlog:sprint:update' => array('b:s:u'),
            'backlog:team:add'      => array('b:t:a'),
            'backlog:team:join'     => array('b:t:j'),
            'backlog:team:list'     => array('b:t:l'),
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
            ->method('getObjectManager')
            ->will($this->returnValue(new NullObjectManager()));
        $this->plugin
            ->expects($this->once())
            ->method('getEntityCreator')
            ->will($this->returnValue(new NullEntityCreator()));
        $this->plugin
            ->expects($this->once())
            ->method('getEntityFinder')
            ->will($this->returnValue(new NullEntityFinder()));

        $this->application->registerPlugin($this->plugin);
    }

    public function testShouldReturnTheConfiguration()
    {
        $this->assertSame(array(), $this->application->getConfiguration());
    }
}
