<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Star\Plugin\Doctrine\DoctrinePlugin;
use tests\UnitTestCase;

/**
 * Class DoctrinePluginTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit
 *
 * @covers Star\Plugin\Doctrine\DoctrinePlugin
 * @uses Star\Plugin\Doctrine\DoctrineObjectManagerAdapter
 * @uses Star\Plugin\Doctrine\Repository\DoctrineRepository
 */
class DoctrinePluginTest extends UnitTestCase
{
    /**
     * @var DoctrinePlugin
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new DoctrinePlugin();
    }

    public function testShouldBePlugin()
    {
        $this->assertInstanceOfPlugin($this->sut);
    }

    public function testShouldReturnFactory()
    {
        $this->assertInstanceOfTeamFactory($this->sut->getTeamFactory());
    }

    public function testShouldBeBuiltProperly()
    {
        $configuration = array(
            'database' => array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ),
        );

        $application = $this->getMock('Star\Component\Sprint\BacklogApplication', array(), array(), '', false);
        $application
            ->expects($this->once())
            ->method('getRootPath')
            ->will($this->returnValue(__DIR__ . '/../../Resources/config/doctrine/'));
        $application
            ->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $this->assertAttributeSame(null, 'objectManager', $this->sut);
        $this->sut->build($application);
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'objectManager', $this->sut);

        $this->assertInstanceOfRepositoryManager($this->sut->getRepositoryManager());
    }
}
 