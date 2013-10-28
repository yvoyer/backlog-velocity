<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprintMemberData;

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
        $team = $this->generateTeam($name);

        /**
         * @var $team Team
         */
        $team = $this->getRefreshedObject($team);

        $this->assertSame($name, $team->getName(), 'Name is not as expected');
    }

    public function testShouldPersistSprinter()
    {
        $name     = uniqid('sprinter-name-');
        $sprinter = $this->generateSprinter($name);

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
        $team       = $this->generateTeam(uniqid('team'));
        $sprinter   = $this->generateSprinter(uniqid('sprinter'));
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
        $team       = $this->generateTeam(uniqid('team'));
        $repository = $this->getEntityManager()->getRepository(SprintData::LONG_NAME);
        $name       = uniqid('sprint');

        $this->assertEmpty($repository->findAll(), 'Sprint list should be empty');
        $sprint = $this->generateSprint($name, $team);
        $this->getRefreshedObject($sprint);
        $this->assertCount(1, $repository->findAll(), 'Sprint list should contain 1 element');

        $this->assertSame($name, $sprint->getName());
    }

    /**
     * @depends testShouldPersistSprinter
     * @depends testShouldPersistTeam
     * @depends testShouldPersistSprint
     */
    public function testShouldPersistSprintMember()
    {
        $availableManDays = 100;
        $actualVelocity   = 200;
        $sprint           = $this->generateSprint(uniqid('sprint'));
        $team             = $this->generateTeam(uniqid('team'));
        $sprinter         = $this->generateSprinter(uniqid('sprinter'));
        $teamMember       = $this->generateTeamMember($sprinter, $team);
        $repository       = $this->getEntityManager()->getRepository(SprintMemberData::LONG_NAME);

        $this->assertEmpty($repository->findAll());
        $sprintMember = $this->generateSprintMember($availableManDays, $actualVelocity, $sprint, $teamMember);
        $this->assertCount(1, $repository->findAll());

        $this->getRefreshedObject($sprintMember);
        $this->assertSame($sprint, $sprintMember->getSprint());
        $this->assertSame($teamMember, $sprintMember->getTeamMember());
        $this->assertSame($availableManDays, $sprintMember->getAvailableManDays());
    }
}
