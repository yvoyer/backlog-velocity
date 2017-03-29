<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Infrastructure\Cli\Command\Team;

use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use tests\UnitTestCase;

/**
 * Class ListCommandTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Command\Team\ListCommand
 * @uses Star\Component\Sprint\Template\ConsoleView
 */
class ListCommandTest extends UnitTestCase
{
    /**
     * @var TeamCollection
     */
    private $repository;

    /**
     * @var ListCommand
     */
    private $command;

    public function setUp()
    {
        $this->repository = new TeamCollection();

        $this->command = new ListCommand($this->repository);
    }

    public function testShouldHaveAName()
    {
        $this->assertSame('backlog:team:list', $this->command->getName());
    }

    public function testShouldHaveADescription()
    {
        $this->assertSame('List the teams.', $this->command->getDescription());
    }

    public function testShouldListAllTeams()
    {
        $this->repository->saveTeam(new TeamModel(TeamId::fromString('id'), new TeamName('name')));

        $display = $this->executeCommand($this->command);

        $expected = <<<DISPLAY
+------+---------+
| Team | Members |
+------+---------+
| name |         |
+------+---------+
DISPLAY;

        $this->assertContains($expected, $display);
    }
}
