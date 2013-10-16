<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
use Star\Component\Sprint\Mapping\Repository\Mapping;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineObjectManagerAdapterTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter
 */
class DoctrineObjectManagerAdapterTest extends UnitTestCase
{
    /**
     * @param ObjectManager $objectManager
     * @param Mapping       $mapping
     *
     * @return DoctrineObjectManagerAdapter
     */
    private function getAdapter(
        ObjectManager $objectManager = null,
        Mapping $mapping = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);
        $mapping = $this->getMockClassMapping($mapping);

        return new DoctrineObjectManagerAdapter($objectManager, $mapping);
    }

    /**
     * @dataProvider provideGetRepositoryManagerMethodsData
     *
     * @param string $type
     */
    public function testShouldReturnTheMappedRepository($type)
    {
        $repositoryMapping = uniqid($type . '-mapping');

        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($repositoryMapping)
            ->will($this->returnValue($this->getMockDoctrineRepository()));

        $mapping = $this->getMockClassMapping();
        $mapping
            ->expects($this->once())
            ->method('get' . $type . 'Mapping')
            ->will($this->returnValue($repositoryMapping));

        $adapter = $this->getAdapter($objectManager, $mapping);
        $this->assertInstanceOf('Star\Component\Sprint\Repository\RepositoryManager', $adapter);

        $createMethod = 'get' . $type . 'Repository';
        $repository = $adapter->{$createMethod}();
        $this->assertInstanceOf('Star\\Component\\Sprint\\Entity\\Repository\\' . $type . 'Repository', $repository);
    }

    public function provideGetRepositoryManagerMethodsData()
    {
        return array(
            array('Team'),
            array('Sprint'),
            array('Sprinter'),
            array('TeamMember'),
            array('SprintMember'),
        );
    }
}
