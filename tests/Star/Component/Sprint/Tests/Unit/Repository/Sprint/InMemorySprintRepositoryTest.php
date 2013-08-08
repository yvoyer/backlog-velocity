<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Sprint;

use Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;

/**
 * Class InMemorySprintRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Sprint
 *
 * @covers Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository
 */
class InMemorySprintRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private function getRepository()
    {
        return new InMemorySprintRepository();
    }

    public function testShouldBeEmptyAtFirst()
    {
        $repository = $this->getRepository();
        $this->assertEmpty($repository->findAll());

        return $repository;
    }

    /**
     * @param InMemorySprintRepository $repository
     *
     * @return InMemorySprintRepository
     *
     * @depends testShouldBeEmptyAtFirst
     */
    public function testShouldAddTheSprint(InMemorySprintRepository $repository)
    {
        $repository->add(new StubIdentifier('Sprint 1'), new Sprint1());
        $this->assertCount(1, $repository->findAll());
        $repository->add(new StubIdentifier('Sprint 2'), new Sprint2());
        $this->assertCount(2, $repository->findAll());
        $repository->add(new StubIdentifier('Sprint 3'), new Sprint3());
        $this->assertCount(3, $repository->findAll());

        return $repository;
    }

    /**
     * @param InMemorySprintRepository $repository
     *
     * @return \Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository
     * @depends testShouldAddTheSprint
     */
    public function testShouldReturnAllTheStubSprint(InMemorySprintRepository $repository)
    {
        $result = $repository->findAll();

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Sprint', $result);

        return $repository;
    }

    /**
     * @param string                                                            $expectedName
     * @param \Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository $repository
     *
     * @dataProvider getExpectedStubSprintData
     *
     * @depends testShouldReturnAllTheStubSprint
     */
    public function testShouldReturnTheSpecificStubSprint($expectedName, InMemorySprintRepository $repository)
    {
        $object = $repository->find(new StubIdentifier($expectedName));

        $this->assertInstanceOf('Star\Component\Sprint\Sprint', $object);
        $this->assertSame($expectedName, $object->getName());
    }

    public function getExpectedStubSprintData()
    {
        return array(
            array('Sprint 1'),
            array('Sprint 2'),
            array('Sprint 3'),
        );
    }
}
