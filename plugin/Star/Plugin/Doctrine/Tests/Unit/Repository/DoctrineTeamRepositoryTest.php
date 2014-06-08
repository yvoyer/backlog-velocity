<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Star\Plugin\Doctrine\Repository\DoctrineTeamRepository;

/**
 * Class DoctrineTeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineTeamRepository
 * @uses Star\Plugin\Doctrine\Repository\DoctrineRepository
 */
class DoctrineTeamRepositoryTest extends DoctrineRepositoryTest
{
    public function setUp()
    {
        parent::setUp();

        $this->repository = new DoctrineTeamRepository($this->wrappedRepository, $this->objectManager);
    }

    public function testShouldFindOneByNameUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('name' => 'name');

        $this->wrappedRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $this->assertSame($result, $this->repository->findOneByName('name'));
    }
}
