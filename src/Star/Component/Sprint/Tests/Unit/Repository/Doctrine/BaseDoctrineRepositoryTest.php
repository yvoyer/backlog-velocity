<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Doctrine;

use Star\Component\Sprint\Repository\Adapter\DoctrineAdapter;
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
     * @param DoctrineAdapter $adapter
     *
     * @return mixed
     */
    protected abstract function getRepository(DoctrineAdapter $adapter = null);

    public function testShouldAddUsingTheAdapter()
    {
        $entity = $this->getMockEntity();

        $adapter = $this->getMockDoctrineAdapter();
        $adapter
            ->expects($this->once())
            ->method('add')
            ->with($entity);

        $this->getRepository($adapter)->add($entity);
    }

    public function testShouldSaveUsingTheAdapter()
    {
        $adapter = $this->getMockDoctrineAdapter();
        $adapter
            ->expects($this->once())
            ->method('save');

        $this->getRepository($adapter)->save();
    }
}
