<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Builder;

use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasClosed;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasCreated;
use Star\BacklogVelocity\Agile\Domain\Model\Event\SprintWasStarted;
use Star\BacklogVelocity\Agile\Domain\Model\Event\TeamMemberCommittedToSprint;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

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
     * @param SprintWasCreated $event
     */
    public function __construct(SprintWasCreated $event) {
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
        $this->events[] = TeamMemberCommittedToSprint::version1(
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
            $this->sprintId,
            Velocity::fromInt($actualVelocity),
            new \DateTimeImmutable($closedAt)
        );

        return $this;
    }

    /**
     * @return Sprint
     */
    public function buildSprint()
    {
        return SprintModel::fromStream($this->events);
    }

    /**
     * @param string $name
     * @param string $projectId
     * @param string $teamId
     * @param string $createdAt
     *
     * @return SprintBuilder
     */
    public static function pending(
        string $name,
        string $projectId,
        string $teamId,
        string $createdAt = 'now'
    ) :SprintBuilder {
        return new self(
            SprintWasCreated::version1(
                SprintId::fromString($name),
                new SprintName($name),
                ProjectId::fromString($projectId),
                TeamId::fromString($teamId),
                new \DateTimeImmutable($createdAt)
            )
        );
    }
}
