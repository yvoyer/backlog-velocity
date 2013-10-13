<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

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
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineRepository
     */
    private function getRepository()
    {
        return $this->getMockForAbstractClass(
            'Star\Component\Sprint\Repository\Doctrine\DoctrineRepository',
            array($this->getMockDoctrineAdapter())
        );
    }

    public function testShouldFindAllUsingTheConfiguredRepository()
    {
        $result = 'result';

        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $repository = $this->getRepository();
        $repository
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($wrappedRepository));

        $this->assertSame($result, $repository->findAll());
    }

    public function testShouldFindOneByUsingTheConfiguredRepository()
    {
        $result   = 'result';
        $criteria = array('something');

        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($result));

        $repository = $this->getRepository();
        $repository
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($wrappedRepository));

        $this->assertSame($result, $repository->findOneBy($criteria));
    }

    public function testShouldSaveUsingTheConfiguredRepository()
    {
        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('save');

        $repository = $this->getRepository();
        $repository
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($wrappedRepository));

        $repository->save();
    }

    public function testShouldAddUsingTheConfiguredRepository()
    {
        $entity = $this->getMockEntity();

        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $repository = $this->getRepository();
        $repository
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($wrappedRepository));

        $repository->add($entity);
    }

    public function testShouldFindUsingTheConfiguredRepository()
    {
        $result = 'result';
        $id     = 421472109;

        $wrappedRepository = $this->getMockRepository();
        $wrappedRepository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $repository = $this->getRepository();
        $repository
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($wrappedRepository));

        $this->assertSame($result, $repository->find($id));
    }
}
