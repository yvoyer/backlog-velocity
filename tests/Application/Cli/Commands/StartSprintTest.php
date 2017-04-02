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
    private $sprint;

    public function setUp()
    {
        $this->sprint = StubSprint::withId(SprintId::fromString('name'));
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
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(123));
        $this->assertSprintIsSaved();

        $this->assertFalse($this->sprint->isStarted());
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' is now started.", $result);
        $this->assertTrue($this->sprint->isStarted());
    }

    public function test_should_not_start_not_found_sprint()
    {
        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint 'name' cannot be found.", $result);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Estimated velocity must be numeric.
     */
    public function test_should_throw_exception_when_no_estimated_velocity_given()
    {
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => ''));
    }

    public function test_should_use_dialog_to_set_estimated_cost()
    {
        $dialog = $this->getMockDialog();
        $dialog
            ->expects($this->once())
            ->method('askAndValidate')
            ->will($this->returnValue(123));
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->command->setHelperSet(new HelperSet(array('dialog' => $dialog)));
        $display = $this->executeCommand($this->command, array('name' => 'name'));
        $this->assertContains("I suggest: 99 man days.", $display);
        $this->assertContains("Sprint 'name' is now started.", $display);
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The dialog helper is not configured.
     */
    public function test_should_throw_exception_when_dialog_not_set()
    {
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(1));
        $this->assertSprintIsSaved();

        $this->command->setHelperSet(new HelperSet());
        $this->executeCommand($this->command, array('name' => 'name'));
    }

    private function assertSprintIsSaved()
    {
        $this->sprintRepository->saveSprint($this->sprint);
    }

    /**
     * @ticket #52
     */
    public function test_should_show_meaningful_message_when_no_man_days_available()
    {
        $this->assertSprintIsSaved();
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(0));
        $this->assertSame(0, $this->sprint->getManDays()->toInt());

        $result = $this->executeCommand($this->command, array('name' => 'name', 'estimated-velocity' => 123));
        $this->assertContains("Sprint member's commitments total should be greater than 0.", $result);
    }

    public function test_it_should_accept_the_suggested_velocity_when_no_specific_velocity_given()
    {
        $this->assertSprintIsSaved();
        $this->sprint->withManDays(ProjectId::fromString('p'), ManDays::fromInt(10));
        $display = $this->executeCommand($this->command, array('name' => 'name', '--accept-suggestion' => true));
        $this->assertContains("I started the sprint 'name' with the suggested velocity of 99 Story points.", $display);
    }

    public function test_it_should_calculate_velocity_with_closed_sprint_of_project_only()
    {
        $this->command = new StartSprint($this->sprintRepository, new ResourceCalculator());
        $projectOne = ProjectId::fromString('p1');
        $projectTwo = ProjectId::fromString('p2');

        $this->assertClosedSprintIsCreated(SprintId::fromString('s1'), $projectOne);
        $this->assertClosedSprintIsCreated(SprintId::fromString('s2'), $projectOne);
        $this->assertClosedSprintIsCreated(SprintId::fromString('s1'), $projectTwo);
        $this->assertClosedSprintIsCreated(SprintId::fromString('s2'), $projectTwo);
        $sprint = new SprintModel(SprintId::fromString('name'), 'name', $projectTwo, new \DateTimeImmutable());
        $sprint->commit(PersonId::fromString('person'), ManDays::fromInt(20));
        $this->sprintRepository->saveSprint($sprint);

        $display = $this->executeCommand($this->command, array('name' => $sprint->getName(), '--accept-suggestion' => true));
        $this->assertContains("I started the sprint 'name' with the suggested velocity of 40 Story points.", $display);
    }

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     */
    private function assertClosedSprintIsCreated(SprintId $sprintId, ProjectId $projectId)
    {
        $this->sprintRepository->saveSprint(
            SprintModel::closedSprint(
                $sprintId,
                uniqid(),
                $projectId,
                Velocity::fromInt(10),
                Velocity::fromInt(10),
                [
                    new CommitmentDTO(PersonId::fromString('person'), ManDays::fromInt(5)),
                ]
            )
        );
    }
}
