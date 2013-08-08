<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit;

use Star\Component\Sprint\Backlog;
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
 * @package Star\Component\Sprint\Tests\Unit
 *
 * @covers Star\Component\Sprint\Backlog
 */
class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Backlog
     */
    private $backlog;

    public function setUp()
    {
        $this->backlog = new Backlog();
    }

    public function testShouldManageSprintCollection()
    {
        $this->assertEmpty($this->backlog->getSprints());
        $this->backlog->addSprint(new Sprint1());
        $this->assertCount(1, $this->backlog->getSprints());
        $this->backlog->addSprint(new Sprint2());
        $this->assertCount(2, $this->backlog->getSprints());
        $this->backlog->addSprint(new Sprint3());
        $this->assertCount(3, $this->backlog->getSprints());
    }

    public function testShouldManageTeamCollection()
    {
        $this->assertEmpty($this->backlog->getTeams());
        $this->backlog->addTeam(new Team1());
        $this->assertCount(1, $this->backlog->getTeams());
        $this->backlog->addTeam(new Team2());
        $this->assertCount(2, $this->backlog->getTeams());
    }

    public function testShouldCreateSprint()
    {
        $sprint = $this->backlog->createSprint('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Sprint', $sprint);
        $this->assertSame('Name', $sprint->getName());
    }

    public function testShouldCreateTeam()
    {
        $team = $this->backlog->createTeam('Name');
        $this->assertInstanceOf('Star\Component\Sprint\Team', $team);
        $this->assertSame('Name', $team->getName());
    }
}
