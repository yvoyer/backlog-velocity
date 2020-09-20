<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Builder\SprintBuilder;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class CloseSprintTest extends CliIntegrationTestCase
{
    /**
     * @var CloseSprint
     */
    private $command;

    /**
     * @var SprintCollection
     */
    private $sprintRepository;

	protected function setUp(): void
    {
        $this->sprintRepository = new SprintCollection();
        $this->command = new CloseSprint($this->sprintRepository);
    }

    public function test_should_close_the_sprint(): void
    {
        $sprint = SprintBuilder::pending(
            'name',
            'project-id',
            'team-id'
        )
            ->committedMember('person-id', 99)
            ->started(99)
            ->buildSprint();

        $this->sprintRepository->saveSprint($sprint);

        $this->assertFalse($sprint->isClosed());
        $result = $this->executeCommand(
            $this->command,
            [
                'name' => 'name',
                'project' => 'project-id',
                'actual-velocity' => 123,
            ]
        );
        $this->assertStringContainsString("Sprint 'name' of project 'project-id' is now closed.", $result);
        $this->assertTrue($sprint->isClosed());
    }

    public function test_should_not_close_not_found_sprint(): void
    {
        $result = $this->executeCommand(
            $this->command,
            [
                'name' => 'name',
                'actual-velocity' => 123,
                'project' => 'project',
            ]
        );
        $this->assertStringContainsString("Sprint 'name' cannot be found in project 'project'.", $result);
    }
}
