<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;

final class CommitMemberToSprintHandlerTest extends TestCase
{
    /**
     * @var SprintCollection
     */
    private $sprints;

    public function setUp()
    {
        $this->sprints = new SprintCollection(
            [
                SprintModel::pendingSprint(
                    SprintId::fromString('s1'),
                    new SprintName('name'),
                    ProjectId::fromString('p1'),
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

        $handler = new CommitMemberToSprintHandler($this->sprints);
        $handler(CommitMemberToSprint::fromString('s1', 'p1', 3));

        $this->assertSame(
            3, $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->getManDays()->toInt()
        );
    }

    public function test_it_should_change_commitment_of_member_when_sprint_not_started()
    {
        $this->markTestSkipped('TODO in another PR');
        $sprint = $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'));
        $sprint->commit(MemberId::fromString('p1'), ManDays::fromInt(10));

        $this->assertSame(
            10, $sprint->getManDays()->toInt()
        );

        $handler = new CommitMemberToSprintHandler($this->sprints);
        $handler(CommitMemberToSprint::fromString('s1', 'p1', 4));

        $this->assertSame(
            4, $this->sprints->getSprintWithIdentity(SprintId::fromString('s1'))->getManDays()->toInt()
        );
    }
}
