<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Sprint;

use Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

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

    public function testShouldReturnAllTheStubSprint()
    {
        $repository = $this->getRepository();
        $result     = $repository->findAll();

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Sprint', $result);
    }

    /**
     * @param $expectedName
     *
     * @dataProvider getExpectedStubSprintData
     */
    public function testShouldReturnTheSpecificStubSprint($expectedName)
    {
        $repository = $this->getRepository();
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
