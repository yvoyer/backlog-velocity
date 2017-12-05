<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Builder;

use Star\Component\Sprint\Domain\Event\SprintWasClosed;
use Star\Component\Sprint\Domain\Event\SprintWasCreatedInProject;
use Star\Component\Sprint\Domain\Event\SprintWasStarted;
use Star\Component\Sprint\Domain\Event\TeamMemberCommitedToSprint;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\SprintName;

final class SprintBuilder
{
    /**
     * @var array
     */
    private $events = [];

    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @param SprintWasCreatedInProject $event
     */
    public function __construct(SprintWasCreatedInProject $event) {
        $this->sprintId = $event->sprintId();
        $this->events[] = $event;
    }

    /**
     * @param string $memberId
     * @param int $manDays
     *
     * @return SprintBuilder
     */
    public function committedMember(string $memberId, int $manDays) :SprintBuilder
    {
        $this->events[] = TeamMemberCommitedToSprint::version1(
            $this->sprintId,
            MemberId::fromString($memberId),
            ManDays::fromInt($manDays)
        );

        return $this;
    }

    /**
     * @param int $estimatedVelocity
     * @param string $startedAt
     *
     * @return SprintBuilder
     */
    public function started(int $estimatedVelocity, string $startedAt = 'now') :SprintBuilder
    {
        $this->events[] = SprintWasStarted::version1(
            $this->sprintId, $estimatedVelocity, new \DateTimeImmutable($startedAt)
        );

        return $this;
    }

    /**
     * @param int $actualVelocity
     * @param string $closedAt
     *
     * @return SprintBuilder
     */
    public function closed(int $actualVelocity, string $closedAt = 'now') :SprintBuilder
    {
        $this->events[] = SprintWasClosed::version1(
            $this->sprintId, $actualVelocity, new \DateTimeImmutable($closedAt)
        );

        return $this;
    }

    /**
     * @return SprintModel
     */
    public function buildSprint()
    {
        return SprintModel::fromStream($this->events);
    }

    public static function pending(
        string $name,
        string $projectName,
        string $createdAt = 'now'
    ) :SprintBuilder {
        return new self(
            SprintWasCreatedInProject::version1(
                SprintId::fromString($name),
                ProjectId::fromString($projectName),
                new SprintName($name),
                new \DateTimeImmutable($createdAt)
            )
        );
    }
}
