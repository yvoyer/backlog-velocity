<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Repository\Adapter\DoctrineAdapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineSprintRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class DoctrineSprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineSprintRepository
 */
class DoctrineSprintRepositoryTest extends UnitTestCase
{
    /**
     * @param DoctrineAdapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineSprintRepository
     */
    private function getRepository(DoctrineAdapter $adapter = null)
    {
        $adapter = $this->getMockDoctrineAdapter($adapter);

        return new DoctrineSprintRepository($adapter);
    }

    public function testShouldFindAllUsingTheAdapter()
    {
        $result = 'result';

        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetSprintRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findAll());
    }

    public function testShouldFindOneByUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('something');

        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetSprintRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneBy($args));
    }

    public function testShouldFindUsingTheAdapter()
    {
        $result = 'result';
        $id     = 754231;

        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetSprintRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
    }

    public function testShouldAddUsingTheAdapter()
    {
        $entity = $this->getMockEntity();

        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $adapter = $this->getMockDoctrineAdapterExpectsGetSprintRepository($repository);
        $this->getRepository($adapter)->add($entity);
    }

    public function testShouldSaveUsingTheAdapter()
    {
        $repository = $this->getMockSprintRepository();
        $repository
            ->expects($this->once())
            ->method('save');

        $adapter = $this->getMockDoctrineAdapterExpectsGetSprintRepository($repository);
        $this->getRepository($adapter)->save();
    }

    /**
     * @param SprintRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineAdapter
     */
    private function getMockDoctrineAdapterExpectsGetSprintRepository(SprintRepository $repository)
    {
        $adapter = $this->getMockDoctrineAdapter();
        $adapter
            ->expects($this->once())
            ->method('getSprintRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
