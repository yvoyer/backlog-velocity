<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
use tests\UnitTestCase;

/**
 * Class DoctrineObjectManagerAdapterTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit
 *
 * @covers Star\Plugin\Doctrine\DoctrineObjectManagerAdapter
 */
class DoctrineObjectManagerAdapterTest extends UnitTestCase
{
    /**
     * @var DoctrineObjectManagerAdapter
     */
    private $repositoryFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repositoryFactory = new DoctrineObjectManagerAdapter($this->objectManager);
    }

    /**
     * @dataProvider provideGetRepositoryManagerMethodsData
     *
     * @param string $type
     */
    public function testShouldReturnTheMappedRepository($type)
    {
        $class = 'Star\\Component\\Sprint\\Model\\' . $type . 'Model';
        $this->objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->will($this->returnValue($this->getMock('Doctrine\Common\Persistence\ObjectRepository')));

        $createMethod = 'get' . $type . 'Repository';
        $repository = $this->repositoryFactory->{$createMethod}();
        $this->assertInstanceOf('Star\\Component\\Sprint\\Entity\\Repository\\' . $type . 'Repository', $repository);
    }

    public function provideGetRepositoryManagerMethodsData()
    {
        return array(
            array('Team'),
            array('Sprint'),
            array('Person'),
            array('TeamMember'),
            array('SprintMember'),
        );
    }
}
