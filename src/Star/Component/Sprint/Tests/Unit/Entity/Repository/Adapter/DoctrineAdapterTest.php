<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Entity\Repository\Adapter;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Repository\Adapter\DoctrineAdapter;
use Star\Component\Sprint\Repository\Mapping;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineAdapterTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Entity\Repository\Adapter
 *
 * @covers Star\Component\Sprint\Repository\Adapter\DoctrineAdapter
 */
class DoctrineAdapterTest extends UnitTestCase
{
    /**
     * @param ObjectManager $objectManager
     * @param Mapping       $mapping
     *
     * @return DoctrineAdapter
     */
    private function getAdapter(
        ObjectManager $objectManager = null,
        Mapping $mapping = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);
        $mapping = $this->getMockClassMapping($mapping);

        return new DoctrineAdapter($objectManager, $mapping);
    }

    /**
     * @dataProvider provideGetRepositoryManagerMethodsData
     *
     * @param $method
     */
    public function testShouldBeARepositoryManager($method)
    {
        $repository = $this->getMock('\Doctrine\Common\Persistence\ObjectRepository');

        $repositoryMapping = uniqid($method . '-mapping');

        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($repositoryMapping)
            ->will($this->returnValue($repository));

        $mapping = $this->getMockClassMapping();
        $mapping
            ->expects($this->once())
            ->method($method . 'Mapping')
            ->will($this->returnValue($repositoryMapping));

        $adapter = $this->getAdapter($objectManager, $mapping);
        $this->assertInstanceOf('Star\Component\Sprint\Repository\RepositoryManager', $adapter);
        $this->assertSame($repository, $adapter->{$method . 'Repository'}());
    }

    public function provideGetRepositoryManagerMethodsData()
    {
        return array(
            array('getTeam'),
            array('getSprint'),
            array('getSprinter'),
            array('getTeamMember'),
            array('getSprintMember'),
        );
    }
}
