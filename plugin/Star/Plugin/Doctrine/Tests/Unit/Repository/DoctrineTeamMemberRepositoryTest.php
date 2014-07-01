<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Plugin\Doctrine\Repository\DoctrineTeamMemberRepository;

/**
 * Class DoctrineTeamMemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineTeamMemberRepository
 * @uses Star\Plugin\Doctrine\Repository\DoctrineRepository
 */
class DoctrineTeamMemberRepositoryTest extends DoctrineRepositoryTest
{
    public function setUp()
    {
        parent::setUp();

        $this->repository = new DoctrineTeamMemberRepository($this->wrappedRepository, $this->objectManager);
    }

    public function test_should_return_null()
    {
        $this->wrappedRepository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array()));

        $this->assertNull($this->repository->findMemberOfSprint('', ''));
    }
}
