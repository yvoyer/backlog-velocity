<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\StubRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class StubRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 */
class StubRepositoryTest extends UnitTestCase
{
    /**
     * @var StubRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = new StubRepository();
    }

    /**
     * @param Team    $team
     * @param integer $expectedId
     * @param string  $expectedName
     * @param integer $memberCount
     */
    private function assertTeam(Team $team, $expectedId, $expectedName, $memberCount)
    {
        $this->assertInstanceOfTeam($team);
        $this->assertSame($expectedId, $team->getId(), 'The id is not as expected.');
        $this->assertSame($expectedName, $team->getName(), 'The name is not as expected.');
        $this->assertCount($memberCount, $team->getMembers(), 'The number of member is not as expected');
    }

    /**
     * @param Sprinter $member
     * @param integer  $expectedId
     * @param string   $expectedName
     */
    private function assertMember(Sprinter $member, $expectedId, $expectedName)
    {
        $this->assertInstanceOfSprinter($member);
        $this->assertSame($expectedId, $member->getId(), 'The id is not as expected.');
        $this->assertSame($expectedName, $member->getName(), 'The name is not as expected.');
    }

    public function testShouldHaveTheImperialTeam()
    {
        $team = $this->repository->findTeam(1);
        $this->assertTeam($team, 1, 'The Galactic Empire', 5);

        $members = $team->getMembers();
        $this->assertMember($members[0], 1, 'Darth Vader');
        $this->assertMember($members[1], 2, 'Senator Palpatine');
        $this->assertMember($members[2], 3, 'Stormtrooper TK-421');
        $this->assertMember($members[3], 4, 'Pilot DS-61-3');
        $this->assertMember($members[4], 10, 'Lando Calrisian');
    }

    public function testShouldHaveTheRebelTeam()
    {
        $team = $this->repository->findTeam(2);
        $this->assertTeam($team, 2, 'The Rebel Alliance', 4);

        $members = $team->getMembers();
        $this->assertMember($members[0], 5, 'Luke Skywalker');
        $this->assertMember($members[1], 6, 'Han Solo');
        $this->assertMember($members[2], 7, 'Leia Skywalker');
        $this->assertMember($members[3], 10, 'Lando Calrisian');
    }

    public function testShouldHaveTheCrimeSyndicate()
    {
        $team = $this->repository->findTeam(3);
        $this->assertTeam($team, 3, 'The Crime Syndicate', 2);

        $members = $team->getMembers();
        $this->assertMember($members[0], 8, 'Jabba The Hutt');
        $this->assertMember($members[1], 9, 'Bobba Fett');
    }

    public function testShouldHaveTheSithTeam()
    {
        $team = $this->repository->findTeam(4);
        $this->assertTeam($team, 4, 'The Siths', 2);

        $members = $team->getMembers();
        $this->assertMember($members[0], 1, 'Darth Vader');
        $this->assertMember($members[1], 2, 'Senator Palpatine');
    }
}
