<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class CreateTeamTest extends CliIntegrationTestCase
{
    /**
     * @var TeamCollection
     */
    private $teamRepository;

    /**
     * @var CreateTeam
     */
    private $command;

	protected function setUp(): void
    {
        $this->teamRepository = new TeamCollection();

        $this->command = new CreateTeam($this->teamRepository);
    }

    public function test_should_be_a_command(): void
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:team:add', 'Add a team.');
    }

    public function test_should_have_a_name_argument(): void
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_use_the_argument_supplied_as_team_name(): void
    {
        $this->assertCount(0, $this->teamRepository->allTeams());
        $content = $this->executeCommand(
            $this->command,
            [
                'name' => 'teamName',
            ]
        );
        $this->assertStringContainsString("The team 'teamName' was successfully saved.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }

    public function test_should_not_add_team_when_the_team_name_already_exists_in_project(): void
    {
        $this->teamRepository->saveTeam(TeamModel::fromString('teamId', 'teamName'));
        $this->assertCount(1, $this->teamRepository->allTeams());

        $content = $this->executeCommand(
            $this->command, [
                'name' => 'teamName',
            ]
        );

        $this->assertStringContainsString("The team 'teamName' already exists.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }
}
