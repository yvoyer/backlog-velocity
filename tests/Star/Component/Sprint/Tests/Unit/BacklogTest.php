<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class BacklogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit
 *
 * @covers Star\Component\Sprint\Backlog
 */
class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param \Star\Component\Sprint\Repository\Repository $sprintRepository
     * @param \Star\Component\Sprint\Repository\Repository $teamRepository
     *
     * @return Backlog
     */
    private function getBacklog(Repository $sprintRepository = null, Repository $teamRepository = null)
    {
        if (null === $sprintRepository) {
            $sprintRepository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        }

        if (null === $teamRepository) {
            $teamRepository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        }

        return new Backlog($sprintRepository, $teamRepository);
    }

    public function testShouldManageSprintCollection()
    {
        $sprint = $this->getMock('Star\Component\Sprint\Sprint', array(), array(), '', false);

        $sprintRepository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        $sprintRepository
            ->expects($this->once())
            ->method('add')
            ->with($sprint);

        $backlog = $this->getBacklog($sprintRepository);
        $backlog->addSprint($sprint);
    }

    public function testShouldManageTeamCollection()
    {
        $team = $this->getMock('Star\Component\Sprint\Team', array(), array(), '', false);

        $teamRepository = $this->getMock('Star\Component\Sprint\Repository\Repository');
        $teamRepository
            ->expects($this->once())
            ->method('add')
            ->with($team);

        $backlog = $this->getBacklog(null, $teamRepository);
        $backlog->addTeam($team);
    }

    public function testShouldCreateSprint()
    {
        $sprint = $this->getBacklog()->createSprint('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Sprint', $sprint);
        $this->assertSame('Name', $sprint->getName());
    }

    public function testShouldCreateTeam()
    {
        $team = $this->getBacklog()->createTeam('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Team', $team);
        $this->assertSame('Name', $team->getName());
    }
}
