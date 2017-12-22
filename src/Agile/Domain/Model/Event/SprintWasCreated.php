<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

final class SprintWasCreated extends AggregateChanged
{
    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return SprintId::fromString($this->aggregateId());
    }

    /**
     * @return SprintName
     */
    public function name()
    {
        return new SprintName($this->payload['name']);
    }

    /**
     * @return \DateTimeInterface
     */
    public function addedAt()
    {
        return new \DateTimeImmutable($this->payload['created_at']);
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return ProjectId::fromString($this->payload['project_id']);
    }

    /**
     * @return TeamId
     */
    public function teamId()
    {
        return TeamId::fromString($this->payload['team_id']);
    }

    /**
     * @param SprintId $id
     * @param SprintName $name
     * @param ProjectId $projectId
     * @param TeamId $teamId
     * @param \DateTimeInterface $createdAt
     *
     * @return static
     */
    public static function version1(
        SprintId $id,
        SprintName $name,
        ProjectId $projectId,
        TeamId $teamId,
        \DateTimeInterface $createdAt
    ) {
        return static::occur(
            $id->toString(),
            [
                'name' => $name->toString(),
                'project_id' => $projectId->toString(),
                'team_id' => $teamId->toString(),
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
            ]
        );
    }
}
