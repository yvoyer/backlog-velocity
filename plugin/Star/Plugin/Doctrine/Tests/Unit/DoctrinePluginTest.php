<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit;

use Star\Component\Sprint\Tests\Unit\UnitTestCase;
use Star\Plugin\Doctrine\DoctrinePlugin;

/**
 * Class DoctrinePluginTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit
 *
 * @covers Star\Plugin\Doctrine\DoctrinePlugin
 */
class DoctrinePluginTest extends UnitTestCase
{
    /**
     * @var DoctrinePlugin
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new DoctrinePlugin($this->getMockOutput());
    }

    public function testShouldBePlugin()
    {
        $this->assertInstanceOfPlugin($this->sut);
    }

    public function testShouldReturnCreator()
    {
        $this->assertInstanceOfEntityCreator($this->sut->getTeamFactory());
    }

    public function testShouldBeBuiltProperly()
    {
        $configuration = array(
            'database' => array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ),
            'root'     => __DIR__,
            'env'      => 'dev',
        );

        $application = $this->getMockBacklogApplication();
        $application
            ->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));

        $this->assertAttributeSame(null, 'objectManager', $this->sut);
        $this->sut->build($application);
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'objectManager', $this->sut);

        $this->assertInstanceOfEntityFinder($this->sut->getEntityFinder());
        $this->assertInstanceOfRepositoryManager($this->sut->getRepositoryManager());
    }
}
 