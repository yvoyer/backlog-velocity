<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter as Adapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineSprinterRepository;

/**
 * Class DoctrineSprinterRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineSprinterRepository
 */
class DoctrineSprinterRepositoryTest extends BaseDoctrineRepositoryTest
{
    /**
     * @param Adapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineSprinterRepository
     */
    protected function getRepository(Adapter $adapter = null)
    {
        $adapter = $this->getMockObjectManagerAdapter($adapter);

        return new DoctrineSprinterRepository($adapter);
    }

    public function testShouldFindAllUsingTheAdapter()
    {
        $result = 'result';

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprinterRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findAll());
    }

    public function testShouldFindOneByUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('something');

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprinterRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneBy($args));
    }

    public function testShouldFindUsingTheAdapter()
    {
        $result = 'result';
        $id     = 41156327;

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprinterRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
    }

    public function testShouldFindOneByNameUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('name' => 'name');

        $repository = $this->getMockSprinterRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprinterRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneByName('name'));
    }

    /**
     * @param SprinterRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Adapter
     */
    private function getMockObjectManagerAdapterExpectsGetSprinterRepository(SprinterRepository $repository)
    {
        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method('getSprinterRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
