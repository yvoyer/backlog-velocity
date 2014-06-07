<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Star\Plugin\Doctrine\Repository\DoctrineRepository;
use tests\UnitTestCase;

/**
 * Class DoctrineRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineRepository
 */
class DoctrineRepositoryTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $wrappedRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var DoctrineRepository
     */
    protected $repository;

    public function setUp()
    {
        $this->objectManager = $this->getMockDoctrineObjectManager();
        $this->wrappedRepository = $this->getMockDoctrineRepository();

        $this->repository = $this->getMockForAbstractClass(
            'Star\Plugin\Doctrine\Repository\DoctrineRepository',
            array($this->wrappedRepository, $this->objectManager)
        );
    }

    public function testShouldFindAllUsingTheConfiguredRepository()
    {
        $result = 'result';

        $this->wrappedRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->repository->findAll());
    }

    public function testShouldFindOneByUsingTheConfiguredRepository()
    {
        $result   = 'result';
        $criteria = array('something');

        $this->wrappedRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->repository->findOneBy($criteria));
    }

    public function testShouldFindUsingTheConfiguredRepository()
    {
        $result = 'result';
        $id     = 421472109;

        $this->wrappedRepository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->repository->find($id));
    }

    public function testShouldAddUsingTheObjectManager()
    {
        $entity = $this->getMockEntity();

        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->repository->add($entity);
    }

    public function testShouldSaveUsingTheObjectManager()
    {
        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->repository->save();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDoctrineObjectManager()
    {
        return $this->getMock('Doctrine\Common\Persistence\ObjectManager');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockDoctrineRepository()
    {
        return $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
    }
}
