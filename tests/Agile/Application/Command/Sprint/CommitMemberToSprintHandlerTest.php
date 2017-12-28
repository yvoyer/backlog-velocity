<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\PersonCollection;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection\SprintCollection;

final class CommitMemberToSprintHandlerTest extends TestCase
{
    /**
     * @var SprintCollection
     */
    private $sprints;

    /**
     * @var PersonCollection
     */
    private $persons;

    public function setUp()
    {
        $this->persons = new PersonCollection(
            [
                PersonModel::fromString('mid', 'm-name'),
            ]
        );
        $this->sprints = new SprintCollection(
            [
                SprintModel::pendingSprint(
                    SprintId::fromString('s1'),
                    new SprintName('name'),
                    ProjectId::fromString('p1'),
                    TeamId::fromString('t1'),
                    new \DateTimeImmutable()
                )
            ]
        );
    }

    public function test_it_should_commit_member_to_sprint()
    {
        $this->assertSame(
            0, $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->getManDays()->toInt()
        );

        $handler = new CommitMemberToSprintHandler($this->sprints, $this->persons);
        $handler(CommitMemberToSprint::fromString('s1', 'mid', 3));

        $this->assertSame(
            3, $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->getManDays()->toInt()
        );
    }

    public function test_it_should_throw_exception_when_member_not_found()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            EntityNotFoundException::objectWithIdentity(MemberId::fromString('m1'))->getMessage()
        );

        $handler = new CommitMemberToSprintHandler($this->sprints, new PersonCollection());
        $handler(CommitMemberToSprint::fromString('s1', 'm1', 3));
    }

    public function test_it_should_change_commitment_of_member_when_sprint_not_started()
    {
        $this->markTestSkipped('TODO in another PR');
        $sprint = $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'));
        $sprint->commit(MemberId::fromString('mid1'), ManDays::fromInt(10));

        $this->assertSame(
            10, $sprint->getManDays()->toInt()
        );

        $handler = new CommitMemberToSprintHandler($this->sprints, $this->persons);
        $handler(CommitMemberToSprint::fromString('s1', 'mid', 4));

        $this->assertSame(
            4, $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->getManDays()->toInt()
        );
    }
}
