<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Tests\Unit\Null\NullEntityCreator;
use Star\Component\Sprint\Tests\Unit\Null\NullEntityFinder;
use Star\Component\Sprint\Tests\Unit\Null\NullObjectManager;
use Star\Component\Sprint\Tests\Unit\Null\NullRepositoryManager;

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

    public function setUp()
    {
        $this->application = new BacklogApplication(
            new NullRepositoryManager(),
            new NullObjectManager(),
            new NullEntityCreator(),
            new NullEntityFinder()
        );
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
}
