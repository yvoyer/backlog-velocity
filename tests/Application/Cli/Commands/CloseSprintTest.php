<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Domain\Builder\SprintBuilder;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\Velocity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CloseSprintTest extends CliIntegrationTestCase
{
    /**
     * @var CloseSprint
     */
    private $command;

    /**
     * @var SprintCollection
     */
    private $sprintRepository;

    public function setUp()
    {
        $this->sprintRepository = new SprintCollection();
        $this->command = new CloseSprint($this->sprintRepository);
    }

    public function test_should_close_the_sprint()
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
        $this->assertContains("Sprint 'name' of project 'project-id' is now closed.", $result);
        $this->assertTrue($sprint->isClosed());
    }

    public function test_should_not_close_not_found_sprint()
    {
        $result = $this->executeCommand(
            $this->command,
            [
                'name' => 'name',
                'actual-velocity' => 123,
                'project' => 'project',
            ]
        );
        $this->assertContains("Sprint 'name' cannot be found in project 'project'.", $result);
    }
}
