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
 */
class DoctrineTeamMemberRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @param string        $repository
     * @param ObjectManager $objectManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineTeamMemberRepository
     */
    protected function getRepository(
        $repository = null,
        ObjectManager $objectManager = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineTeamMemberRepository($repository, $objectManager);
    }
}
