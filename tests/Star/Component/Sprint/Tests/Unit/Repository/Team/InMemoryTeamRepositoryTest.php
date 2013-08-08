<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Team;

use Star\Component\Sprint\Repository\Team\InMemoryTeamRepository;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;
use Star\Component\Sprint\Tests\Stub\Team\Team1;
use Star\Component\Sprint\Tests\Stub\Team\Team2;

/**
 * Class InMemoryTeamRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository\Team
 *
 * @covers Star\Component\Sprint\Repository\Team\InMemoryTeamRepository
 */
class InMemoryTeamRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private function getRepository()
    {
        return new InMemoryTeamRepository();
    }

    public function testShouldBeEmptyAtFirst()
    {
        $repository = $this->getRepository();
        $this->assertEmpty($repository->findAll());

        return $repository;
    }

    /**
     * @param InMemoryTeamRepository $repository
     *
     * @return InMemoryTeamRepository
     *
     * @depends testShouldBeEmptyAtFirst
     */
    public function testShouldAddObject(InMemoryTeamRepository $repository)
    {
        $repository->add(new StubIdentifier('Team 1'), new Team1());
        $this->assertCount(1, $repository->findAll());
        $repository->add(new StubIdentifier('Team 2'), new Team2());
        $this->assertCount(2, $repository->findAll());

        return $repository;
    }

    /**
     * @param InMemoryTeamRepository $repository
     *
     * @depends testShouldAddObject
     */
    public function testShouldReturnAllTheStubTeams(InMemoryTeamRepository $repository)
    {
        $result = $repository->findAll();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Team', $result);

        return $repository;
    }

    /**
     * @param $expectedName
     *
     * @param \Star\Component\Sprint\Repository\Team\InMemoryTeamRepository $repository
     * @dataProvider getExpectedStubTeamData
     * @depends testShouldReturnAllTheStubTeams
     */
    public function testShouldReturnTheSpecificStubTeam($expectedName, InMemoryTeamRepository $repository)
    {
        $object = $repository->find(new StubIdentifier($expectedName));

        $this->assertInstanceOf('Star\Component\Sprint\Team', $object);
        $this->assertSame($expectedName, $object->getName());
    }

    public function getExpectedStubTeamData()
    {
        return array(
            array('Team 1'),
            array('Team 2'),
        );
    }
}
