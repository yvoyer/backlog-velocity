<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\TeamModel;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListTeamsTest extends CliIntegrationTestCase
{
    /**
     * @var TeamCollection
     */
    private $repository;

    /**
     * @var ListTeams
     */
    private $command;

    public function setUp()
    {
        $this->repository = new TeamCollection();

        $this->command = new ListTeams($this->repository);
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
        $team = TeamModel::fromString('id', 'name');
        $team->addTeamMember(PersonModel::fromString('person-id', 'person-name'));
        $this->repository->saveTeam($team);

        $display = $this->executeCommand($this->command);

        $expected = <<<DISPLAY
List of team's details:
+------+-----------+
| Team | Members   |
+------+-----------+
| name |           |
|      | person-id |
+------+-----------+

DISPLAY;

        $this->assertSame($expected, $display);
    }
}
