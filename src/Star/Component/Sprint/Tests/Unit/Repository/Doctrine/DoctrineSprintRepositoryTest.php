<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
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
class DoctrineSprintRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @param string        $repository
     * @param ObjectManager $objectManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineSprintRepository
     */
    protected function getRepository(
        $repository = null,
        ObjectManager $objectManager = null
    ) {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineSprintRepository($repository, $objectManager);
    }
}
