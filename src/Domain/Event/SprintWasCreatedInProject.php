<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\SprintName;

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
     * @param SprintId $id
     * @param SprintName $name
     * @param \DateTimeInterface $createdAt
     *
     * @return static
     */
    public static function version1(SprintId $id, SprintName $name, \DateTimeInterface $createdAt)
    {
        return static::occur(
            $id->toString(),
            [
                'name' => $name->toString(),
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
            ]
        );
    }
}
