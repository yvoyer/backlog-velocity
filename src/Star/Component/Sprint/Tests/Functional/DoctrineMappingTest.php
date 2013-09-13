<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

/**
 * Class DoctrineMappingTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 */
class DoctrineMappingTest extends FunctionalTestCase
{
    public function testShouldPersistTeam()
    {
        $name = uniqid('team');
        $team = $this->createTeam($name);

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);

        $this->assertSame($name, $team->getName(), 'Name is not as expected');
    }

    public function testShouldPersistSprinter()
    {
        $name     = uniqid('sprinter-name-');
        $sprinter = $this->createSprinter($name);

        /**
         * @var $sprinter Sprinter
         */
        $sprinter = $this->getRefreshedObject($sprinter);

        $this->assertSame($name, $sprinter->getName(), 'Name is not as expected');
    }

    /**
     * @depends testShouldPersistTeam
     * @depends testShouldPersistSprinter
     */
    public function testShouldPersistTeamMember()
    {
        $team       = $this->createTeam(uniqid('team'));
        $sprinter   = $this->createSprinter(uniqid('sprinter'));
        $teamMember = $team->addMember($sprinter);

        $em = $this->getEntityManager();
        $em->persist($teamMember);
        $em->flush();

        /**
         * @var $teamMember TeamMember
         */
        $teamMember = $this->getRefreshedObject($teamMember);
        $this->assertInstanceOfSprinter($teamMember->getMember());
        $this->assertInstanceOfTeam($teamMember->getTeam());

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);
        $this->assertCount(1, $team->getMembers());
    }

    /**
     * @depends testShouldPersistTeam
     */
    public function testShouldPersistSprint()
    {
        $team       = $this->createTeam(uniqid('team'));
        $repository = $this->getEntityManager()->getRepository(Sprint::LONG_NAME);
        $name       = uniqid('sprint');

        $this->assertEmpty($repository->findAll(), 'Sprint list should be empty');
        $sprint = $this->createSprint($name);
        $this->getRefreshedObject($sprint);
        $this->assertCount(1, $repository->findAll(), 'Sprint list should contain 1 element');

        $this->assertSame($name, $sprint->getName());
    }

//    /**
//     * @depends testShouldPersistTeamMember
//     */
//    public function testShouldPersistSprintMember()
//    {
//
//    }
}