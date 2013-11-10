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
        $this->sut = new DoctrinePlugin($this->getMockDoctrineObjectManager(), $this->getMockOutput());
    }

    public function testShouldBePlugin()
    {
        $this->assertInstanceOfPlugin($this->sut);
    }

    public function testShouldReturnCreator()
    {
        $this->assertInstanceOfEntityCreator($this->sut->getEntityCreator());
    }

    public function testShouldReturnFinder()
    {
        $this->assertInstanceOfEntityFinder($this->sut->getEntityFinder());
    }

    public function testShouldReturnRepositoryManager()
    {
        $this->assertInstanceOfRepositoryManager($this->sut->getRepositoryManager());
    }

    public function testShouldReturnObjectManager()
    {
        $this->assertInstanceOfObjectManager($this->sut->getObjectManager());
    }
}
 