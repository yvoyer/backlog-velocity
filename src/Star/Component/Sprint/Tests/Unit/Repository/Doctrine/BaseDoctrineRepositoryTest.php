<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Repository\Adapter\ObjectManagerAdapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class BaseDoctrineRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Doctrine
 */
abstract class BaseDoctrineRepositoryTest extends UnitTestCase
{
    /**
     * Returns the unit under test.
     *
     * @param DoctrineObjectManagerAdapter $adapter
     *
     * @return mixed
     */
    protected abstract function getRepository(DoctrineObjectManagerAdapter $adapter = null);

    public function testShouldAddUsingTheAdapter()
    {
        $entity = $this->getMockEntity();

        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->getRepository($adapter)->add($entity);
    }

    public function testShouldSaveUsingTheAdapter()
    {
        $adapter = $this->getMockObjectManagerAdapter();
        $adapter
            ->expects($this->once())
            ->method('save');

        $this->getRepository($adapter)->save();
    }
}
