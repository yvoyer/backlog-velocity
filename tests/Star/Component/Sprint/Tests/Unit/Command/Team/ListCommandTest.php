<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Command\Team;

use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Command\Team
 *
 * @covers Star\Component\Sprint\Command\Team\ListCommand
 */
class ListCommandTest extends UnitTestCase
{
    /**
     * @param \Star\Component\Sprint\Entity\Repository\TeamRepository $repository
     *
     * @return ListCommand
     */
    private function getCommand(TeamRepository $repository = null)
    {
        $repository = $this->getMockTeamRepository($repository);

        return new ListCommand($repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:team:list', $this->getCommand()->getName());
    }

    public function testShouldHaveADescription()
    {
        $this->assertSame('List the teams', $this->getCommand()->getDescription());
    }

    public function testShouldListAllTeams()
    {
        $name = uniqid('name');
        $team = array('name' => $name);
        // @todo Refactor to use object with mapping
        // $team = $this->getMockTeam();
        // $team
        //    ->expects($this->once())
        //    ->method('getName')
        //    ->will($this->returnValue($name));

        $repository = $this->getMockTeamRepository();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array($team)));

        $command = $this->getCommand($repository);
        $display = $this->executeCommand($command);

        $this->assertContains($name, $display);
    }
}
