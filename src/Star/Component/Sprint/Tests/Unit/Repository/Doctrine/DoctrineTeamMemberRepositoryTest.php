<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Repository\Adapter\DoctrineAdapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineTeamMemberRepository;

/**
 * Class DoctrineTeamMemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 *
 * @covers Star\Component\Sprint\Repository\Doctrine\DoctrineTeamMemberRepository
 */
class DoctrineTeamMemberRepositoryTest extends BaseDoctrineRepositoryTest
{
    /**
     * @param DoctrineAdapter $adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineTeamMemberRepository
     */
    protected function getRepository(DoctrineAdapter $adapter = null)
    {
        $adapter = $this->getMockDoctrineAdapter($adapter);

        return new DoctrineTeamMemberRepository($adapter);
    }

    public function testShouldFindAllUsingTheAdapter()
    {
        $result = 'result';

        $repository = $this->getMockTeamMemberRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamMemberRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findAll());
    }

    public function testShouldFindOneByUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('something');

        $repository = $this->getMockTeamMemberRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamMemberRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->findOneBy($args));
    }

    public function testShouldFindUsingTheAdapter()
    {
        $result = 'result';
        $id     = 9876543;

        $repository = $this->getMockTeamMemberRepository();
        $repository
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->will($this->returnValue($result));

        $adapter = $this->getMockDoctrineAdapterExpectsGetTeamMemberRepository($repository);
        $this->assertSame($result, $this->getRepository($adapter)->find($id));
    }

    /**
     * @param TeamMemberRepository $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineAdapter
     */
    private function getMockDoctrineAdapterExpectsGetTeamMemberRepository(TeamMemberRepository $repository)
    {
        $adapter = $this->getMockDoctrineAdapter();
        $adapter
            ->expects($this->once())
            ->method('getTeamMemberRepository')
            ->will($this->returnValue($repository));

        return $adapter;
    }
}
