<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\SprintModel;
use tests\UnitTestCase;

/**
 * Class SprintModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Model\SprintModel
 * @uses Star\Component\Sprint\Calculator\FocusCalculator
 * @uses Star\Component\Sprint\Collection\SprintMemberCollection
 * @uses Star\Component\Sprint\Model\SprintMemberModel
 * @uses Star\Component\Sprint\Model\Identity\SprintId
 * @uses Star\Component\Sprint\Type\String
 */
class SprintModelTest extends UnitTestCase
{
    const EXPECTED_ID = 'eb1b26ca-899e-4177-8b82-24bc98cf25bc';
    /**
     * @var SprintModel
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = new SprintModel(
            SprintId::fromString(self::EXPECTED_ID), 'name', ProjectId::fromString('project'), new \DateTime()
        );
    }

    public function test_should_be_a_sprint()
    {
        $this->assertInstanceOfSprint($this->sprint);
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->sprint->getName());
    }

    public function test_should_return_the_sprint_project()
    {
        $this->assertEquals(ProjectId::fromString('project'), $this->sprint->projectId());
    }

    public function test_should_return_the_actual_velocity()
    {
        $this->assertSame(0, $this->sprint->getActualVelocity());
        $this->assertSprintIsStarted();
        $this->sprint->close(40, new \DateTime());
        $this->assertSame(40, $this->sprint->getActualVelocity());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The name can't be empty.
     */
    public function test_should_have_a_valid_name()
    {
        new SprintModel(SprintId::uuid(), '', ProjectId::fromString('p'), new \DateTime());
    }

    public function test_should_define_estimated_velocity()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->assertSame(0, $this->sprint->getEstimatedVelocity());
        $this->sprint->start(46, new \DateTime());
        $this->assertSame(46, $this->sprint->getEstimatedVelocity());
    }

    public function test_starting_sprint_should_start_it()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started by default');
        $this->sprint->start(46, new \DateTime());
        $this->assertTrue($this->sprint->isStarted(), 'The sprint should be started');
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\AlreadyStartedSprintException
     * @expectedExceptionMessage The sprint is already started.
     */
    public function test_should_throw_exception_when_sprint_is_already_started()
    {
        $this->assertSprintIsStarted();
        $this->sprint->start(39, new \DateTime());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Cannot close a sprint that is not started.
     */
    public function test_throw_exception_when_closing_a_not_started_sprint()
    {
        $this->assertFalse($this->sprint->isStarted());
        $this->sprint->close(123, new \DateTime());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage Cannot close a sprint that is already closed.
     */
    public function test_throw_exception_when_closing_a_closed_sprint()
    {
        $this->assertSprintIsClosed();
        $this->sprint->close(123, new \DateTime());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\NoSprintMemberException
     * @expectedExceptionMessage Cannot start a sprint with no sprint members.
     */
    public function test_throw_exception_when_starting_a_sprint_with_no_member()
    {
        $this->assertEmpty($this->sprint->getCommitments());
        $this->sprint->start(123, new \DateTime());
    }

    /**
     * @depends test_starting_sprint_should_start_it
     */
    public function test_closing_sprint_should_close_it()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->sprint->start(46, new \DateTime());
        $this->assertFalse($this->sprint->isClosed(), 'The sprint should not be closed');
        $this->sprint->close(34, new \DateTime());
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started');
        $this->assertTrue($this->sprint->isClosed(), 'The sprint should be closed');
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\SprintNotClosedException
     * @expectedExceptionMessage The sprint is not closed, the focus cannot be determined.
     */
    public function test_should_throw_exception_when_getting_focus_on_not_closed_sprint()
    {
        $this->assertFalse($this->sprint->isClosed(), 'Sprint should not be closed');
        $this->sprint->getFocusFactor();
    }

    public function test_should_have_a_focus_factor()
    {
        $this->sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(50));
        $this->sprint->start(rand(), new \DateTime());
        $this->sprint->close(25, new \DateTime());
        $this->assertSame(50, $this->sprint->getFocusFactor());
    }

    public function test_should_return_the_id()
    {
        $this->assertSame(self::EXPECTED_ID, $this->sprint->getId()->toString());
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\AlreadyCommittedSprintMemberException
     * @expectedExceptionMessage The sprint member 'person-name' is already committed.
     */
    public function test_should_throw_exception_when_sprint_member_already_added()
    {
        $this->sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(43));
        $this->sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(43));
    }

    public function test_should_add_sprint_member_to_sprint()
    {
        $this->assertCount(0, $this->sprint->getCommitments());
        $this->sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(12));
        $this->assertCount(1, $this->sprint->getCommitments());
    }

    public function test_it_should_match_project_id()
    {
        $this->assertTrue($this->sprint->matchProject(ProjectId::fromString('project')));
        $this->assertFalse($this->sprint->matchProject(ProjectId::fromString('invalid-project')));
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\SprintNotStartedException
     * @expectedExceptionMessage Cannot ask for start date when the sprint was never started.
     */
    public function test_it_should_throw_an_exception_when_asking_for_started_date_on_never_started_sprint()
    {
        $this->assertFalse($this->sprint->isStarted());
        $this->sprint->startedAt();
    }

    /**
     * @expectedException        \Star\Component\Sprint\Exception\Sprint\SprintNotClosedException
     * @expectedExceptionMessage Cannot ask for end date when the sprint is not closed.
     */
    public function test_it_should_throw_an_exception_when_asking_for_ended_date_on_not_ended_sprint()
    {
        $this->assertFalse($this->sprint->isClosed());
        $this->sprint->endedAt();
    }

    private function assertSprintHasAtLeastOneMember()
    {
        $this->sprint->commit(PersonId::fromString('person-name'), ManDays::fromInt(43));
        $this->assertNotEmpty($this->sprint->getCommitments());
    }

    private function assertSprintIsStarted()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->sprint->start($velocity = rand(), new \DateTime('2003-04-05'));
        $this->assertTrue($this->sprint->isStarted(), 'Sprint should be started');
        $this->assertInstanceOf(\DateTimeInterface::class, $this->sprint->startedAt());
        $this->assertSame('2003-04-05', $this->sprint->startedAt()->format('Y-m-d'));
        $this->assertSame($velocity, $this->sprint->getEstimatedVelocity(), 'Velocity should be set');
    }

    private function assertSprintIsClosed()
    {
        $this->assertSprintIsStarted();
        $this->sprint->close(rand(), new \DateTime('2002-01-06'));
        $this->assertInstanceOf(\DateTimeInterface::class, $this->sprint->endedAt());
        $this->assertSame('2002-01-06', $this->sprint->endedAt()->format('Y-m-d'));
        $this->assertTrue($this->sprint->isClosed(), 'Sprint should be closed');
    }
}
