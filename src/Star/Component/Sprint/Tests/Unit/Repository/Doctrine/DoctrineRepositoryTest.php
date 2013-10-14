<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineRepository
 */
class DoctrineRepositoryTest extends UnitTestCase
{
    /**
     * @param string        $repository
     * @param ObjectManager $objectManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineRepository
     */
    protected function getRepository(
        $repository = null,
        ObjectManager $objectManager = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return $this->getMockForAbstractClass(
            'Star\Component\Sprint\Repository\Doctrine\DoctrineRepository',
            array($repository, $objectManager)
        );
    }

    /**
     * @param string           $repositoryType
     * @param ObjectRepository $repository
     *
     * @return ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDoctrineObjectManagerExpectsGetRepository(
        $repositoryType,
        ObjectRepository $repository = null
    ) {
        $wrappedRepository = $this->getMockDoctrineRepository($repository);

        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('getRepository')
            ->with($repositoryType)
            ->will($this->returnValue($wrappedRepository));

        return $objectManager;
    }

    public function testShouldFindAllUsingTheConfiguredRepository()
    {
        $result = 'result';
        $type   = 'type';

        $wrappedRepository = $this->getMockDoctrineRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository($type, $wrappedRepository);

        $repository = $this->getRepository($type, $objectManager);
        $this->assertSame($result, $repository->findAll());
    }

    public function testShouldFindOneByUsingTheConfiguredRepository()
    {
        $result   = 'result';
        $type     = 'type';
        $criteria = array('something');

        $wrappedRepository = $this->getMockDoctrineRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($result));

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository($type, $wrappedRepository);

        $repository = $this->getRepository($type, $objectManager);
        $this->assertSame($result, $repository->findOneBy($criteria));
    }

    public function testShouldFindUsingTheConfiguredRepository()
    {
        $result = 'result';
        $type   = 'type';
        $id     = 421472109;

        $wrappedRepository = $this->getMockDoctrineRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository($type, $wrappedRepository);

        $repository = $this->getRepository($type, $objectManager);
        $this->assertSame($result, $repository->find($id));
    }

    public function testShouldAddUsingTheObjectManager()
    {
        $entity = $this->getMockEntity();

        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->getRepository(null, $objectManager)->add($entity);
    }

    public function testShouldSaveUsingTheObjectManager()
    {
        $objectManager = $this->getMockDoctrineObjectManager();
        $objectManager
            ->expects($this->once())
            ->method('flush');

        $this->getRepository(null, $objectManager)->save();
    }
}
