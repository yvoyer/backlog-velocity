<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\InMemoryRepository;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;
use Star\Component\Sprint\Tests\Stub\Team\Team1;
use Star\Component\Sprint\Tests\Stub\Team\Team2;

/**
 * Class BacklogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 *
 * @covers Star\Component\Sprint\Backlog
 */
class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Sprint[] $sprints
     * @param Team[]   $teams
     *
     * @return Backlog
     */
    private function getBacklog(array $sprints = array(), array $teams = array())
    {
        $sprintRepository = new SprintRepository(new InMemoryRepository());
        foreach ($sprints as $sprint) {
            $sprintRepository->add($sprint);
        }

        $teamRepository = new TeamRepository(new InMemoryRepository());
        foreach ($teams as $team) {
            $teamRepository->add($team);
        }

        return new Backlog($sprintRepository, $teamRepository);
    }

    public function testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable()
    {
        $backlog = $this->getBacklog();
        $availableManDays = 50;
        $this->assertSame(35, $backlog->calculateEstimatedVelocity($availableManDays));
    }

    /**
     * @depends testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable
     */
    public function testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity()
    {
        $sprints = array(new Sprint1());
        $availableManDays = 50;

        $this->assertSame(25, $this->getBacklog($sprints)->calculateEstimatedVelocity($availableManDays));
    }

    /**
     * @depends testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity
     */
    public function testShouldCalculateTheThirdSprintBasedOnTwoPastSprint()
    {
        $sprints = array(new Sprint1(), new Sprint2());
        $availableManDays = 50;

        $this->assertSame(32, $this->getBacklog($sprints)->calculateEstimatedVelocity($availableManDays));
    }

    /**
     * @depends testShouldCalculateTheThirdSprintBasedOnTwoPastSprint
     */
    public function testShouldCalculateTheFourthSprintBasedOnThreePastSprint()
    {
        $sprints = array(new Sprint1(), new Sprint2(), new Sprint3());
        $availableManDays = 50;

        $this->assertSame(33, $this->getBacklog($sprints)->calculateEstimatedVelocity($availableManDays));
    }

    public function testShouldCreateTeam()
    {
        $backlog = $this->getBacklog();
        $team    = $backlog->createTeam('Team name');
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Team', $team);
        $this->assertSame('Team name', $team->getName());
    }

    public function testShouldCreateInstanceWhenTeamWhenItDoNotExistsBeforeAddingItToSprint()
    {
        $backlog = $this->getBacklog();
        $this->assertEmpty($backlog->getTeams(), 'The team collection should be empty');
        $this->assertEmpty($backlog->getSprints(), 'The sprint collection should be empty');
        $backlog->addTeamToSprint('Team name', 'Sprint 1');
        $this->assertCount(1, $backlog->getTeams(), 'The team collection should contain 1 element');
        $this->assertCount(1, $backlog->getSprints(), 'The sprint collection should contain 1 element');

        return $backlog;
    }

    /**
     * @depends testShouldCreateInstanceWhenTeamWhenItDoNotExistsBeforeAddingItToSprint
     *
     * @param Backlog $backlog
     */
    public function testShouldUseExistingInstanceWhenSprintAlreadyExists(Backlog $backlog)
    {
        $this->assertCount(1, $backlog->getTeams(), 'The team collection should contain 1 element');
        $this->assertCount(1, $backlog->getSprints(), 'The sprint collection should contain 1 element');
        $backlog->addTeamToSprint('Team name 2', 'Sprint 1');
        $this->assertCount(2, $backlog->getTeams(), 'The new team should be created');
        $this->assertCount(1, $backlog->getSprints(), 'The sprint should be already be added');
    }
}
