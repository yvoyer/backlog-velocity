<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\Adapter\DoctrineAdapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineTeamRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineTeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineTeamRepository
 */
class DoctrineTeamRepositoryTest extends UnitTestCase
{
    /**
     * @param DoctrineAdapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineTeamRepository
     */
    private function getRepository(DoctrineAdapter $adapter = null)
    {
        $adapter = $this->getMockDoctrineAdapter($adapter);

        return new DoctrineTeamRepository($adapter);
    }

    public function testShouldFindAllUsingTheAdapter()
    {
        $result = 'result';

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findAll());
    }

    public function testShouldFindOneByUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('something');

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneBy($args));
    }

    public function testShouldFindUsingTheAdapter()
    {
        $result = 'result';
        $id     = 3156127;

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
    }

    public function testShouldAddUsingTheAdapter()
    {
        $entity = $this->getMockEntity();

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->getRepository($adapter)->add($entity);
    }

    public function testShouldSaveUsingTheAdapter()
    {
        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('save');

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->getRepository($adapter)->save();
    }

    public function testShouldFindOneByNameUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('name' => 'name');

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneByName('name'));
    }

    /**
     * @param TeamRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineAdapter
     */
    private function getMockDoctrineAdapterExpectsGetTeamRepository(TeamRepository $repository)
    {
        $adapter = $this->getMockDoctrineAdapter();
        $adapter
            ->expects($this->once())
            ->method('getTeamRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
