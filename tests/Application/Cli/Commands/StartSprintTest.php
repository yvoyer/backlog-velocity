<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Calculator\AlwaysReturnsVelocity;
use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\SprintName;
use Star\Component\Sprint\Model\Velocity;
use Star\Component\Sprint\Port\CommitmentDTO;
use Symfony\Component\Console\Helper\HelperSet;
use Star\Component\Sprint\Stub\Sprint\StubSprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class StartSprintTest extends CliIntegrationTestCase
{
    /**
     * @var StartSprint
     */
    private $command;

    /**
     * @var SprintCollection
     */
    private $sprintRepository;

    /**
     * @var StubSprint
     */
    private $pendingSprint;

    /**
     * @var StubSprint
     */
    private $startedSprint;

    /**
     * @var StubSprint
     */
    private $closedSprint;

    public function setUp()
    {
        $projectId = ProjectId::fromString('project-id');
        $personId = PersonId::fromString('person-one');
        $this->pendingSprint = SprintModel::notStartedSprint(
            SprintId::uuid(),
            new SprintName('pending-sprint'),
            $projectId,
            new \DateTime()
        );
        $this->startedSprint = SprintModel::startedSprint(
            SprintId::uuid(),
            new SprintName('started-sprint'),
            $projectId,
            Velocity::fromInt(10),
            [new CommitmentDTO($personId, ManDays::fromInt(5))]
        );
        $this->closedSprint = SprintModel::closedSprint(
            SprintId::uuid(),
            new SprintName('closed-sprint'),
            $projectId,
            Velocity::fromInt(15),
            Velocity::fromInt(10),
            [new CommitmentDTO($personId, ManDays::fromInt(5))]
        );
        $this->sprintRepository = new SprintCollection();
        $this->command = new StartSprint($this->sprintRepository, new AlwaysReturnsVelocity(99));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockDialog()
    {
        return $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function test_should_start_the_sprint()
    {
        $this->pendingSprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->assertFalse($this->pendingSprint->isStarted());
        $this->assertSame(0, $this->pendingSprint->getEstimatedVelocity());

        $result = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'estimated-velocity' => 123,
            ]
        );

        $this->assertContains("Sprint 'pending-sprint' is now started.", $result);
        $this->assertTrue($this->pendingSprint->isStarted());
        $this->assertSame(123, $this->pendingSprint->getEstimatedVelocity());
    }

    public function test_should_not_start_not_found_sprint()
    {
        $result = $this->executeCommand(
            $this->command,
            [
                'name' => 'name',
                'project' => 'p',
                'estimated-velocity' => 123,
            ]
        );
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }

    public function test_should_throw_exception_when_no_estimated_velocity_given()
    {
        $this->pendingSprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'estimated-velocity' => '',
            ]
        );
        $this->assertContains('Estimated velocity must be numeric.', $display);
    }

    public function test_should_use_dialog_to_set_estimated_cost()
    {
        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('askAndValidate')
            ->will($this->returnValue(123));
        $this->pendingSprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->command->setHelperSet(new HelperSet(array('dialog' => $dialog)));
        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
            ]
        );
        $this->assertContains("I suggest: 99 man days.", $display);
        $this->assertContains("Sprint 'pending-sprint' is now started.", $display);
        $this->assertSame(123, $this->pendingSprint->getEstimatedVelocity());
    }

    public function test_should_throw_exception_when_dialog_not_set()
    {
        $this->pendingSprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->command->setHelperSet(new HelperSet());
        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
            ]
        );
        $this->assertContains('The dialog helper is not configured.', $display);
    }

    /**
     * @ticket #52
     */
    public function test_should_show_meaningful_message_when_no_man_days_available()
    {
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'estimated-velocity' => 123,
            ]
        );

        $this->assertContains("Cannot start a sprint with no sprint members.", $display);
    }

    public function test_it_should_accept_the_suggested_velocity_when_no_specific_velocity_given()
    {
        $this->pendingSprint->commit(PersonId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);
        $this->assertSame(0, $this->pendingSprint->getEstimatedVelocity());

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                '--accept-suggestion' => true,
            ]
        );

        $this->assertContains("I started the sprint 'pending-sprint' with the suggested velocity of 99 Story points.", $display);
        $this->assertSame(99, $this->pendingSprint->getEstimatedVelocity());
    }

    public function test_it_should_calculate_velocity_with_closed_sprint_of_project_only()
    {
        $this->sprintRepository->saveSprint($this->pendingSprint);
        $this->sprintRepository->saveSprint($this->startedSprint);
        $this->sprintRepository->saveSprint($this->closedSprint);
        $projectId = $this->pendingSprint->projectId();

        $this->command = new StartSprint($this->sprintRepository, new ResourceCalculator());
        $sprint = SprintModel::notStartedSprint(
            SprintId::uuid(), new SprintName('name'), $projectId, new \DateTimeImmutable()
        );
        $sprint->commit(PersonId::fromString('person'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($sprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $sprint->getName()->toString(),
                'project' => $projectId->toString(),
                '--accept-suggestion' => true,
            ]
        );
        $this->assertContains("I started the sprint 'name' with the suggested velocity of 40 Story points.", $display);
    }
}
