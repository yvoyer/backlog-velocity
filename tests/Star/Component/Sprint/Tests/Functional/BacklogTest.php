<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\Repository\Sprint\InMemorySprintRepository;
use Star\Component\Sprint\Repository\Team\InMemoryTeamRepository;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint\Sprint3;

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
     * @return Backlog
     */
    private function getBacklog()
    {
        return new Backlog(new InMemorySprintRepository(), new InMemoryTeamRepository());
    }

    /**
     * @return Backlog
     */
    public function testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable()
    {
        $backlog = $this->getBacklog();
        $availableManDays = 50;
        $this->assertSame(35, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable
     *
     * @param Backlog $backlog
     *
     * @return \Star\Component\Sprint\Backlog
     */
    public function testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint1());
        $this->assertSame(25, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity
     *
     * @param Backlog $backlog
     *
     * @return \Star\Component\Sprint\Backlog
     */
    public function testShouldCalculateTheThirdSprintBasedOnTwoPastSprint(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint2());
        $this->assertSame(32, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateTheThirdSprintBasedOnTwoPastSprint
     *
     * @param Backlog $backlog
     */
    public function testShouldCalculateTheFourthSprintBasedOnThreePastSprint(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint3());
        $this->assertSame(33, $backlog->calculateEstimatedVelocity($availableManDays));
    }

    public function testShouldCreateTeam()
    {
        $backlog = $this->getBacklog();
        $team    = $backlog->createTeam('Team name');
        $this->assertInstanceOf('Star\Component\Sprint\Team', $team);
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
