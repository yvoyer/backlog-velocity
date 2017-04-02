<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

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
        $sprint = StubSprint::withId(SprintId::fromString('name'));
        $this->sprintRepository->saveSprint($sprint);

        $this->assertFalse($sprint->isClosed());
        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' is now closed.", $result);
        $this->assertTrue($sprint->isClosed());
    }

    public function test_should_not_close_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'actual-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }
}
