<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Builder\SprintBuilder;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintModelTest extends TestCase
{
    const EXPECTED_ID = 'eb1b26ca-899e-4177-8b82-24bc98cf25bc';
    /**
     * @var SprintModel
     */
    private $sprint;

    /**
     * @var ProjectId
     */
    private $project;

    public function setUp()
    {
        $this->sprint = SprintModel::pendingSprint(
            SprintId::fromString(self::EXPECTED_ID),
            new SprintName('name'),
            $this->project = ProjectId::fromString('id'),
            TeamId::fromString('tid'),
            new \DateTime('2100-01-01')
        );
    }

    public function test_should_return_the_name()
    {
        $this->assertSame('name', $this->sprint->getName()->toString());
    }

    public function test_should_return_the_sprint_project()
    {
        $this->assertEquals($this->project, $this->sprint->projectId());
    }

    public function test_should_return_the_actual_velocity()
    {
        $this->assertSame(0, $this->sprint->getActualVelocity()->toInt());
        $this->assertSprintIsStarted();
        $this->sprint->close(Velocity::fromInt(40), new \DateTime());
        $this->assertSame(40, $this->sprint->getActualVelocity()->toInt());
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidAssertionException
     * @expectedExceptionMessage Sprint name "" is empty, but non empty value was expected.
     */
    public function test_should_have_a_valid_name()
    {
        SprintModel::pendingSprint(
            SprintId::uuid(),
            new SprintName(''),
            ProjectId::fromString('id'),
            TeamId::uuid(),
            new \DateTime()
        );
    }

    public function test_should_define_estimated_velocity()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->assertSame(0, $this->sprint->getPlannedVelocity()->toInt());
        $this->sprint->start(46, new \DateTime());
        $this->assertSame(46, $this->sprint->getPlannedVelocity()->toInt());
    }

    public function test_starting_sprint_should_start_it()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started by default');
        $this->sprint->start(46, new \DateTime());
        $this->assertTrue($this->sprint->isStarted(), 'The sprint should be started');
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'start' is not allowed when context 'Star\BacklogVelocity\Agile\Domain\Model\SprintModel' is in state 'started'.
     */
    public function test_should_throw_exception_when_sprint_is_already_started()
    {
        $this->assertSprintIsStarted();
        $this->sprint->start(39, new \DateTime());
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'close' is not allowed when context 'Star\BacklogVelocity\Agile\Domain\Model\SprintModel' is in state 'pending'.
     */
    public function test_throw_exception_when_closing_a_not_started_sprint()
    {
        $this->assertFalse($this->sprint->isStarted());
        $this->sprint->close(Velocity::fromInt(123), new \DateTime());
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'close' is not allowed when context 'Star\BacklogVelocity\Agile\Domain\Model\SprintModel' is in state 'closed'.
     */
    public function test_throw_exception_when_closing_a_closed_sprint()
    {
        $this->assertSprintIsClosed();
        $this->sprint->close(Velocity::fromInt(123), new \DateTime());
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\NoSprintMemberException
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
        $this->sprint->close(Velocity::fromInt(34), new \DateTime());
        $this->assertFalse($this->sprint->isStarted(), 'The sprint should not be started');
        $this->assertTrue($this->sprint->isClosed(), 'The sprint should be closed');
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintNotClosedException
     * @expectedExceptionMessage The sprint is not closed, the focus cannot be determined.
     */
    public function test_should_throw_exception_when_getting_focus_on_not_closed_sprint()
    {
        $this->assertFalse($this->sprint->isClosed(), 'Sprint should not be closed');
        $this->sprint->getFocusFactor();
    }

    public function test_should_have_a_focus_factor()
    {
        $this->sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(mt_rand()));
        $this->sprint->start(100, new \DateTime());
        $this->sprint->close(Velocity::fromInt(50), new \DateTime());
        $this->assertInstanceOf(FocusFactor::class, $this->sprint->getFocusFactor());
        $this->assertSame(50, $this->sprint->getFocusFactor()->toInt());
    }

    public function test_should_return_the_id()
    {
        $this->assertSame(self::EXPECTED_ID, $this->sprint->getId()->toString());
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\AlreadyCommittedSprintMemberException
     * @expectedExceptionMessage The sprint member 'person-name' is already committed.
     */
    public function test_should_throw_exception_when_sprint_member_already_added()
    {
        $this->sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(43));
        $this->sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(43));
    }

    public function test_should_add_sprint_member_to_sprint()
    {
        $this->assertCount(0, $this->sprint->getCommitments());
        $this->sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(12));
        $this->assertCount(1, $this->sprint->getCommitments());
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintNotStartedException
     * @expectedExceptionMessage Cannot ask for start date when the sprint was never started.
     */
    public function test_it_should_throw_an_exception_when_asking_for_started_date_on_never_started_sprint()
    {
        $this->assertFalse($this->sprint->isStarted());
        $this->sprint->startedAt();
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintNotClosedException
     * @expectedExceptionMessage Cannot ask for end date when the sprint is not closed.
     */
    public function test_it_should_throw_an_exception_when_asking_for_ended_date_on_not_ended_sprint()
    {
        $this->assertFalse($this->sprint->isClosed());
        $this->sprint->endedAt();
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidAssertionException
     * @expectedExceptionMessage Cannot commit with a number of days not greater than zero, "0" given.
     */
    public function test_it_should_not_allow_to_commit_with_no_man_days()
    {
        $this->sprint->commit(MemberId::fromString('id'), ManDays::fromInt(0));
    }

    /**
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException
     * @expectedExceptionMessage The sprint end date cannot be lower than the start date.
     */
    public function test_it_should_not_allow_end_date_lower_than_started_date()
    {
        $this->sprint->commit(MemberId::fromString('id'), ManDays::fromInt(3));
        $this->sprint->start(12, new \DateTime('2000-10-02'));
        $this->sprint->close(Velocity::fromInt(34), new \DateTime('2000-10-01'));
    }

    /**
     * @ticket #55
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'start' is not allowed when context 'Star\BacklogVelocity\Agile\Domain\Model\SprintModel' is in state 'closed'.
     */
    public function test_it_should_not_allow_to_start_a_closed_sprint() {
        $this->assertSprintIsClosed();
        $this->sprint->start(123, new \DateTime());
    }

    /**
     * @ticket #62
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintLogicException
     * @expectedExceptionMessage Cannot commit sprint when sprint is in state 'started'.
     */
    public function test_it_should_not_allow_to_commit_member_on_started_sprint() {
        $this->assertSprintIsStarted();
        $this->sprint->commit(MemberId::fromString('p1'), ManDays::fromInt(12));
    }

    /**
     * @ticket #62
     * @expectedException        \Star\BacklogVelocity\Agile\Domain\Model\Exception\SprintLogicException
     * @expectedExceptionMessage Cannot commit sprint when sprint is in state 'closed'.
     */
    public function test_it_should_not_allow_to_commit_member_on_closed_sprint() {
        $sprint = SprintBuilder::pending(
                'name',
                'pid',
                'tid'
        )
            ->committedMember('mid', 2)
            ->started(3)
            ->closed(3)
            ->buildSprint();

        $this->assertTrue($sprint->isClosed());
        $sprint->commit(MemberId::fromString('other'), ManDays::fromInt(2));
    }

    public function test_it_should_return_the_started_date_of_a_closed_sprint()
    {
        $sprint = SprintBuilder::pending(
            'name',
            'pid',
            'tid'
        )
            ->committedMember('mid', 2)
            ->started(12)
            ->closed(34)
            ->buildSprint();

        $this->assertInstanceOf(\DateTimeInterface::class, $sprint->startedAt());
        $this->assertSame(date('Y-m-d'), $sprint->startedAt()->format('Y-m-d'));
    }

    public function test_sprint_should_be_linked_to_a_team() {
        $sprint = SprintBuilder::pending('sid', 'pid', 'tid')
            ->buildSprint();

        $this->assertInstanceOf(SprintModel::class, $sprint);
        $this->assertInstanceOf(TeamId::class, $sprint->teamId());
        $this->assertSame('tid', $sprint->teamId()->toString());
    }

    public function test_it_should_have_a_created_date()
    {
        $this->assertSame('2100-01-01', $this->sprint->createdAt()->format('Y-m-d'));
    }

    /**
     * @dataProvider getFocusFactorComputationData
     *
     * @param int $expected
     * @param int $actual
     * @param int $planned
     */
    public function test_it_should_compute_the_current_focus_on_close(int $expected, int $actual, int $planned)
    {
        $this->sprint->commit(MemberId::fromString('m'), ManDays::fromInt(99));
        $this->sprint->start($planned, new \DateTimeImmutable());
        $this->sprint->close(Velocity::fromInt($actual), new \DateTimeImmutable());
        $focus = $this->sprint->getFocusFactor();
        $this->assertInstanceOf(FocusFactor::class, $focus);
        $this->assertSame($expected, $focus->toInt());
    }

    public function getFocusFactorComputationData()
    {
        return array(
            'Focus should be actual / planned' => array(50, 30, 60),
            'Should round down' => array(41, 50, 120),
            array(21, 17, 80),
            'Should allow higher than 100' => array(193, 58, 30),
            'Should round up' => array(101, 56, 55),
        );
    }

    private function assertSprintHasAtLeastOneMember()
    {
        $this->sprint->commit(MemberId::fromString('person-name'), ManDays::fromInt(12));
        $this->assertNotEmpty($this->sprint->getCommitments());
    }

    private function assertSprintIsStarted()
    {
        $this->assertSprintHasAtLeastOneMember();
        $this->sprint->start(34, new \DateTime('2003-04-05'));
        $this->assertTrue($this->sprint->isStarted(), 'Sprint should be started');
        $this->assertInstanceOf(\DateTimeInterface::class, $this->sprint->startedAt());
        $this->assertSame('2003-04-05', $this->sprint->startedAt()->format('Y-m-d'));
        $this->assertSame(34, $this->sprint->getPlannedVelocity()->toInt(), 'Velocity should be set');
    }

    private function assertSprintIsClosed()
    {
        $this->assertSprintIsStarted();
        $this->sprint->close(Velocity::fromInt(56), new \DateTime('2004-01-06'));
        $this->assertInstanceOf(\DateTimeInterface::class, $this->sprint->endedAt());
        $this->assertSame('2004-01-06', $this->sprint->endedAt()->format('Y-m-d'));
        $this->assertTrue($this->sprint->isClosed(), 'Sprint should be closed');
    }
}
