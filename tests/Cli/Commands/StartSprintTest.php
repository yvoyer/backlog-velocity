<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Calculator\AlwaysReturnsVelocity;
use Star\BacklogVelocity\Agile\Domain\Builder\SprintBuilder;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Stub\StubSprint;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class StartSprintTest extends CliIntegrationTestCase
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

	protected function setUp(): void
    {
        $projectId = 'project-id';
        $memberId = 'person-one';
        $teamId = 'team-id';

        $this->pendingSprint = SprintBuilder::pending(
            'pending-sprint',
            $projectId,
            $teamId
        )->buildSprint();

        $this->startedSprint = SprintBuilder::pending(
            'started-sprint',
            $projectId,
            $teamId
        )
            ->committedMember($memberId, 10)
            ->started(10)
            ->buildSprint();

        $this->closedSprint = SprintBuilder::pending(
            'closed-sprint',
            $projectId,
            $teamId
        )
            ->committedMember($memberId, 5)
            ->started(15)
            ->closed(10)
            ->buildSprint();

        $this->sprintRepository = new SprintCollection();
        $this->command = new StartSprint($this->sprintRepository, new AlwaysReturnsVelocity(99));
    }

    private function getMockDialogThatReturnsValue(int $value): QuestionHelper
    {
    	$dialog = $this->getMockBuilder(QuestionHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
	    $dialog
		    ->expects($this->once())
		    ->method('ask')
		    ->will($this->returnValue($value));

	    return $dialog;
    }

    public function test_should_start_the_sprint(): void
    {
        $this->pendingSprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->assertFalse($this->pendingSprint->isStarted());
        $this->assertSame(0, $this->pendingSprint->getPlannedVelocity()->toInt());

        $result = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'planned-velocity' => 123,
            ]
        );

        $this->assertStringContainsString("Sprint 'pending-sprint' is now started.", $result);
        $this->assertTrue($this->pendingSprint->isStarted());
        $this->assertSame(123, $this->pendingSprint->getPlannedVelocity()->toInt());
    }

    public function test_should_not_start_not_found_sprint(): void
    {
        $result = $this->executeCommand(
            $this->command,
            [
                'name' => 'name',
                'project' => 'p',
                'planned-velocity' => 123,
            ]
        );
        $this->assertStringContainsString("Sprint 'name' cannot be found.", $result);
    }

    public function test_should_throw_exception_when_no_estimated_velocity_given(): void
    {
        $this->pendingSprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'planned-velocity' => '',
            ]
        );
        $this->assertStringContainsString('Planned velocity must be numeric.', $display);
    }

    public function test_should_use_dialog_to_set_estimated_cost(): void
    {
        $dialog = $this->getMockDialogThatReturnsValue(123);
        $this->pendingSprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->command->setHelperSet(new HelperSet(array('dialog' => $dialog)));
        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
            ]
        );
        $this->assertStringContainsString("I suggest: 99 man days.", $display);
        $this->assertStringContainsString("Sprint 'pending-sprint' is now started.", $display);
        $this->assertSame(123, $this->pendingSprint->getPlannedVelocity()->toInt());
    }

    public function test_should_throw_exception_when_dialog_not_set(): void
    {
        $this->pendingSprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $this->command->setHelperSet(new HelperSet());
        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
            ]
        );
        $this->assertStringContainsString('The dialog helper is not configured.', $display);
    }

    /**
     * @ticket #52
     */
    public function test_should_show_meaningful_message_when_no_man_days_available(): void
    {
        $this->sprintRepository->saveSprint($this->pendingSprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                'planned-velocity' => 123,
            ]
        );

        $this->assertStringContainsString("Cannot start a sprint with no sprint members.", $display);
    }

    public function test_it_should_accept_the_suggested_velocity_when_no_specific_velocity_given(): void
    {
        $this->pendingSprint->commit(MemberId::fromString('person-id'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($this->pendingSprint);
        $this->assertSame(0, $this->pendingSprint->getPlannedVelocity()->toInt());

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $this->pendingSprint->getName()->toString(),
                'project' => $this->pendingSprint->projectId()->toString(),
                '--accept-suggestion' => true,
            ]
        );

        $this->assertStringContainsString("I started the sprint 'pending-sprint' with the suggested velocity of 99 Story points.", $display);
        $this->assertSame(99, $this->pendingSprint->getPlannedVelocity()->toInt());
    }

    public function test_it_should_calculate_velocity_with_closed_sprint_of_project_only(): void
    {
        $this->sprintRepository->saveSprint($this->pendingSprint);
        $this->sprintRepository->saveSprint($this->startedSprint);
        $this->sprintRepository->saveSprint($this->closedSprint);
        $projectId = $this->pendingSprint->projectId();

        $this->command = new StartSprint($this->sprintRepository, new AlwaysReturnsVelocity(40));
        $sprint = SprintBuilder::pending(
            'name', $projectId->toString(), $this->pendingSprint->teamId()->toString()
        )
            ->committedMember('person', 20)
            ->buildSprint();
        $this->sprintRepository->saveSprint($sprint);

        $display = $this->executeCommand(
            $this->command,
            [
                'name' => $sprint->getName()->toString(),
                'project' => $projectId->toString(),
                '--accept-suggestion' => true,
            ]
        );
        $this->assertStringContainsString("I started the sprint 'name' with the suggested velocity of 40 Story points.", $display);
    }
}
