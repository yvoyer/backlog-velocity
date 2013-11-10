<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Plugin\Doctrine\Repository\DoctrineSprinterRepository;

/**
 * Class DoctrineSprinterRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Tests\Unit\Repository
 *
 * @covers Star\Plugin\Doctrine\Repository\DoctrineSprinterRepository
 */
class DoctrineSprinterRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @param null|string   $type
     * @param ObjectManager $objectManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DoctrineSprinterRepository
     */
    protected function getRepository($type = null, ObjectManager $objectManager = null)
    {
        $objectManager = $this->getMockDoctrineObjectManager($objectManager);

        return new DoctrineSprinterRepository($type, $objectManager);
    }

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

        $this->assertSame($result, $this->getRepository($type, $objectManager)->findOneByName('name'));
    }
}
