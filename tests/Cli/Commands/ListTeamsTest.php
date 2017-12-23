<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;

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
