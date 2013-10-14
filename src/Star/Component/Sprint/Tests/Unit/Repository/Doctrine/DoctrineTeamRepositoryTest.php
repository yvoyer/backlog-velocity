<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter as Adapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineTeamRepository;

/**
 * Class DoctrineTeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineTeamRepository
 */
class DoctrineTeamRepositoryTest extends BaseDoctrineRepositoryTest
{
    /**
     * @param Adapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineTeamRepository
     */
    protected function getRepository(Adapter $adapter = null)
    {
        $adapter = $this->getMockObjectManagerAdapter($adapter);

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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetTeamRepository($repository);
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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetTeamRepository($repository);
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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
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

        $adapter = $this->getMockObjectManagerAdapterExpectsGetTeamRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneByName('name'));
    }

    /**
     * @param TeamRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Adapter
     */
    private function getMockObjectManagerAdapterExpectsGetTeamRepository(TeamRepository $repository)
    {
        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method('getTeamRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
