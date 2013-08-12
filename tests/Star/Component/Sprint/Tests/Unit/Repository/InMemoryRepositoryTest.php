<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Repository\InMemoryRepository;

/**
 * Class InMemorySprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\InMemoryRepository
 */
class InMemoryRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return InMemoryRepository
     */
    private function getRepository()
    {
        return new InMemoryRepository();
    }

    public function testShouldBeRepository()
    {
        $this->assertInstanceOf('Star\Component\Sprint\Repository\Repository', $this->getRepository());
    }

    public function testShouldBeEmptyAtFirst()
    {
        $repository = $this->getRepository();
        $this->assertEmpty($repository->findAll());

        return $repository;
    }

    /**
     * @param InMemoryRepository $repository
     *
     * @return InMemoryRepository
     *
     * @depends testShouldBeEmptyAtFirst
     */
    public function testShouldAddEntity(InMemoryRepository $repository)
    {
        $entity = $this->getMock('Star\Component\Sprint\Entity\EntityInterface');
        $entity
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $repository->add($entity);
        $this->assertCount(1, $repository->findAll());
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Entity\EntityInterface', $repository->findAll());
    }

    public function testShouldReturnTheSpecificObject()
    {
        $entity = $this->getMock('Star\Component\Sprint\Entity\EntityInterface');
        $entity
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $repository = $this->getRepository();
        $repository->add($entity);
        $object = $repository->find(1);

        $this->assertSame($entity, $object);
    }

    public function testShouldReturnNullWhenIdNotFound()
    {
        $repository = $this->getRepository();
        $this->assertNull($repository->find(1));
    }

    public function testShouldReturnNullWhenNotFound()
    {
        $id = $this->getMock('Star\Component\Sprint\Entity\IdentifierInterface');
        $this->assertNull($this->getRepository()->find($id));
    }
}
