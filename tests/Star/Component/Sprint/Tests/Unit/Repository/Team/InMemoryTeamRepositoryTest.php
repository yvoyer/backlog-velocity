<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository\Team;

use Star\Component\Sprint\Repository\Team\InMemoryTeamRepository;
use Star\Component\Sprint\Tests\Stub\Entity\StubIdentifier;

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

    public function testShouldReturnAllTheStubTeams()
    {
        $repository = $this->getRepository();
        $result     = $repository->findAll();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf('Star\Component\Sprint\Team', $result);
    }

    /**
     * @param $expectedName
     *
     * @dataProvider getExpectedStubTeamData
     */
    public function testShouldReturnTheSpecificStubTeam($expectedName)
    {
        $repository = $this->getRepository();
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
