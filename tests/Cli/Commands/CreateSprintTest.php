<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\TeamCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class CreateSprintTest extends CliIntegrationTestCase
{
    /**
     * @var CreateSprint
     */
    private $command;

    /**
     * @var ProjectCollection
     */
    private $projects;

    /**
     * @var SprintCollection
     */
    private $sprintRepository;

    /**
     * @var TeamCollection
     */
    private $teams;

    public function setUp()
    {
        $this->teams = new TeamCollection(
            [
                TeamModel::fromString('tid', 'Team 1'),
            ]
        );
        $this->projects = new ProjectCollection();
        $this->sprintRepository = new SprintCollection();
        $this->command = new CreateSprint(
            $this->projects,
            $this->sprintRepository,
            $this->teams
        );
    }

    public function test_should_be_a_command()
    {
        $this->assertInstanceOfCommand($this->command, 'backlog:sprint:add', 'Create a new sprint for the team.');
    }

    /**
     * @dataProvider provideSupportedArgumentData
     */
    public function test_should_have_an_argument($argument)
    {
        $this->assertCommandHasArgument($this->command, $argument, null, true);
    }

    public function provideSupportedArgumentData()
    {
        return array(
            array('name'),
            array('project'),
        );
    }

    /**
     * @depends test_should_be_a_command
     */
    public function test_should_persist_the_input_sprint_in_repository()
    {
        $this->projects->saveProject(
            ProjectAggregate::emptyProject($projectId = ProjectId::fromString('id'), new ProjectName('name'))
        );

        $this->assertNull($this->sprintRepository->activeSprintOfProject($projectId));
        $display = $this->executeCommand(
            $this->command,
            array(
                'name' => 'Some sprint',
                'project' => 'id',
                'team' => 'tid',
            )
        );
        $this->assertContains('The sprint was successfully saved.', $display);
        $this->assertInstanceOf(Sprint::class, $this->sprintRepository->activeSprintOfProject($projectId));
    }

    public function test_should_exit_when_project_not_found()
    {
        $display = $this->executeCommand(
            $this->command,
            array(
                'name' => 'sprint-name',
                'project' => 'invalid-name',
                'team' => 'tid',
            )
        );
        $this->assertContains(
            EntityNotFoundException::objectWithIdentity(ProjectId::fromString('invalid-name'))->getMessage(),
            $display
        );
    }
}
