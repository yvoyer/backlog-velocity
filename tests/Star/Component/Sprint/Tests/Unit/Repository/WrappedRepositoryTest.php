<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Repository\Repository;
use Star\Component\Sprint\Repository\WrappedRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class WrappedRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\WrappedRepository
 */
class WrappedRepositoryTest extends UnitTestCase
{
    /**
     * @param Repository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|WrappedRepository
     */
    private function getRepository(Repository $repository = null)
    {
        $repository = $this->getMockRepository($repository);

        return $this->getMockForAbstractClass(
            'Star\Component\Sprint\Repository\WrappedRepository',
            array($repository)
        );
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $this->getRepository());
    }

    public function testShouldCallWrappedObjectForFindAll()
    {
        $result = mt_rand();

        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->getRepository($repository)->findAll());
    }

    public function testShouldCallWrappedObjectForFind()
    {
        $result = mt_rand();
        $id     = mt_rand();

        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->getRepository($repository)->find($id));
    }

    public function testShouldCallWrappedObjectForSave()
    {
        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('save');

        $this->getRepository($repository)->save();
    }

    public function testShouldCallWrappedObjectForAdd()
    {
        $object = $this->getMockEntity();

        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($object);

        $this->getRepository($repository)->add($object);
    }

    public function testShouldCallWrappedObjectForFindOneBy()
    {
        $criteria = array('key' => 'value');
        $result   = 'object-aklsjgfbakjfvblnvvm';

        $repository = $this->getMockRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->getRepository($repository)->findOneBy($criteria));
    }
}
