<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Repository\InMemoryRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class InMemorySprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\InMemoryRepository
 */
class InMemoryRepositoryTest extends UnitTestCase
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
        $this->assertInternalType('array', $repository->findAll());
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
        $entity = $this->getMockEntity();
        $entity
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));

        $repository->add($entity);
        $this->assertCount(1, $repository->findAll());
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Mapping\Entity', $repository->findAll());
    }

    public function testShouldReturnTheSpecificObject()
    {
        $entity = $this->getMockEntity();
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

    public function testShouldDoNothingOnSave()
    {
        $this->assertTrue($this->getRepository()->save(), 'Save is expected to do nothing');
    }
}
