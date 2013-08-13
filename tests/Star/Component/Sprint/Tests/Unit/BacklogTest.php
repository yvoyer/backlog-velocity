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

    public function testShouldCreateSprint()
    {
        $sprint = $this->getBacklog()->createSprint('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Sprint', $sprint);
        $this->assertSame('Name', $sprint->getName());
    }

    public function testShouldCreateTeam()
    {
        $team = $this->getBacklog()->createTeam('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Entity\Team', $team);
        $this->assertSame('Name', $team->getName());
    }
}
