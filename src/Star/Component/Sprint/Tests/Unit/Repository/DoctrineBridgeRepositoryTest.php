<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Repository\DoctrineBridgeRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineBridgeRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\DoctrineBridgeRepository
 */
class DoctrineBridgeRepositoryTest extends UnitTestCase
{
    /**
     * @param string                                     $entityClass
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     *
     * @return DoctrineBridgeRepository
     */
    private function getRepository($entityClass = '', ObjectManager $objectManager = null)
    {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineBridgeRepository($entityClass, $objectManager);
    }

    public function testShouldBeRepository()
    {
        $repository = $this->getRepository();
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $repository);
    }

    public function testShouldFindAllUsingTheWrappedRepository()
    {
        $result    = array();
        $className = 'SomeEntity';

        $classRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $classRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $om = $this->getMockDoctrineObjectManager();
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with($className)
            ->will($this->returnValue($classRepository));

        $this->assertSame($result, $this->getRepository($className, $om)->findAll());
    }

    public function testShouldFindTheIdUsingTheWrappedRepository()
    {
        $result    = array();
        $className = 'SomeEntity';
        $id        = mt_rand();

        $classRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $classRepository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $om = $this->getMockDoctrineObjectManager();
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with($className)
            ->will($this->returnValue($classRepository));

        $this->assertSame($result, $this->getRepository($className, $om)->find($id));
    }

    public function testShouldAddTheObjectToTheWrappedObjectManager()
    {
        $object = $this->getMockEntity();

        $om = $this->getMockDoctrineObjectManager();
        $om
            ->expects($this->once())
            ->method('persist')
            ->with($object);

        $this->getRepository('', $om)->add($object);
    }

    public function testShouldFlushTheAddObjectsToTheWrappedObjectManager()
    {
        $om = $this->getMockDoctrineObjectManager();
        $om
            ->expects($this->once())
            ->method('flush');

        $this->getRepository('', $om)->save();
    }

    public function testShouldFindTheObjectBasedOnCriteria()
    {
        $result    = 'object-kandkjsabflfvafnf';
        $className = 'SomeEntity';
        $criteria  = array('key' => 'index');

        $classRepository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $classRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($result));

        $om = $this->getMockDoctrineObjectManager();
        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with($className)
            ->will($this->returnValue($classRepository));

        $this->assertSame($result, $this->getRepository($className, $om)->findOneBy($criteria));
    }
}
