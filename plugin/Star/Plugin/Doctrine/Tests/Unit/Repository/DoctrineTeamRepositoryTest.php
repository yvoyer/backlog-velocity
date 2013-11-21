<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Plugin\Doctrine\Repository\DoctrineTeamRepository;

/**
 * Class DoctrineTeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineTeamRepository
 */
class DoctrineTeamRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @param string        $repository
     * @param ObjectManager $objectManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineTeamRepository
     */
    protected function getRepository(
        $repository = null,
        ObjectManager $objectManager = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineTeamRepository($repository, $objectManager);
    }

    public function testShouldFindOneByNameUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('name' => 'name');
        $type   = 'some type';

        $repository = $this->getMockDoctrineRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository($type, $repository);
        $this->assertSame($result, $this->getRepository($type, $objectManager)->findOneByName('name'));
    }
}