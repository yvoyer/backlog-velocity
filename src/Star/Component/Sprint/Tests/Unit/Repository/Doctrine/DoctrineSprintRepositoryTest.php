<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter as Adapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineSprintRepository;

/**
 * Class DoctrineSprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineSprintRepository
 */
class DoctrineSprintRepositoryTest extends BaseDoctrineRepositoryTest
{
    /**
     * @param Adapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineSprintRepository
     */
    protected function getRepository(Adapter $adapter = null)
    {
        $adapter = $this->getMockObjectManagerAdapter($adapter);

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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprintRepository($repository);
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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprintRepository($repository);
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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetSprintRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
    }

    /**
     * @param SprintRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Adapter
     */
    private function getMockObjectManagerAdapterExpectsGetSprintRepository(SprintRepository $repository)
    {
        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method('getSprintRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
