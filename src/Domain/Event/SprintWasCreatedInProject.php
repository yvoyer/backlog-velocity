<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\SprintName;

final class SprintWasCreatedInProject extends AggregateChanged
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
     * @param SprintId $id
     * @param ProjectId $projectId
     * @param SprintName $name
     * @param \DateTimeInterface $createdAt
     *
     * @return static
     */
    public static function version1(SprintId $id, ProjectId $projectId, SprintName $name, \DateTimeInterface $createdAt)
    {
        return static::occur(
            $id->toString(),
            [
                'project_id' => $projectId->toString(),
                'name' => $name->toString(),
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
            ]
        );
    }
}
