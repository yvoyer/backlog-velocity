<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Plugin\Null\Entity\NullProject;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreateTeamTest extends CliIntegrationTestCase
{
    /**
     * @var TeamCollection
     */
    private $teamRepository;

    /**
     * @var ProjectCollection
     */
    private $projects;

    /**
     * @var CreateTeam
     */
    private $command;

    public function setUp()
    {
        $this->teamRepository = new TeamCollection();

        $this->command = new CreateTeam(
            $this->teamRepository,
            $this->projects = new ProjectCollection()
        );
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:team:add', 'Add a team.');
    }

    public function test_should_have_a_name_argument()
    {
        $this->assertCommandHasArgument($this->command, 'name', null, true);
    }

    public function test_should_throw_exception_when_project_id_not_supplied()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The option --project must be supplied.');
        $this->executeCommand($this->command, array('name' => 'teamName'));
    }

    public function test_should_use_the_argument_supplied_as_team_name()
    {
        $this->projects->saveProject($project = new NullProject());
        $this->assertCount(0, $this->teamRepository->allTeams());
        $content = $this->executeCommand(
            $this->command,
            [
                'name' => 'teamName',
                '--project' => $project->getIdentity()->toString(),
            ]
        );
        $this->assertContains("The team 'teamName' was successfully saved.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }

    public function test_should_not_add_team_when_the_team_name_already_exists_in_project()
    {
        $this->projects->saveProject($project = new NullProject());
        $this->teamRepository->saveTeam(
            new TeamModel(
                TeamId::fromString('teamName'),
                new TeamName('teamName'),
                $project
            )
        );
        $this->assertCount(1, $this->teamRepository->allTeams());

        $content = $this->executeCommand(
            $this->command, [
                'name' => 'teamName',
                '--project' => $project->getIdentity()->toString(),
            ]
        );

        $this->assertContains("The team 'teamName' already exists.", $content);
        $this->assertCount(1, $this->teamRepository->allTeams());
    }

    public function test_should_add_team_when_the_team_name_already_exists_in_another_project()
    {
        $this->markTestIncomplete('todo');
    }
}
