<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository;

/**
 * Class DoctrineSprintMemberRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprintMemberRepository
 */
class DoctrineSprintMemberRepositoryTest extends DoctrineRepositoryTest
{
    public function testShouldFindOneByNameUsingTheAdapter()
    {
        $result = 'result';
        $args   = array('name' => 'name');
        $type   = 'my repos';

        $repository = $this->getMockDoctrineRepository();
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($args)
            ->will($this->returnValue($result));

        $objectManager = $this->getMockDoctrineObjectManagerExpectsGetRepository($type, $repository);

        $repository = new DoctrineSprintMemberRepository($type, $objectManager);
        $this->assertSame($result, $repository->findOneByName('name'));
    }
}
