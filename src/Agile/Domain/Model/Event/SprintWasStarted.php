<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;

final class SprintWasStarted extends AggregateChanged
{
    /**
     * @param SprintId $sprintId
     * @param $estimatedVelocity
     * @param \DateTimeInterface $startedAt
     *
     * @return SprintWasStarted
     */
    public static function version1(SprintId $sprintId, $estimatedVelocity, \DateTimeInterface $startedAt)
    {
        return new self(
            $sprintId->toString(),
            [
                'estimated_velocity' => $estimatedVelocity,
                'started_at' => $startedAt->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @return SprintId
     */
    public function sprintId() :SprintId
    {
        return SprintId::fromString($this->aggregateId());
    }

    /**
     * @return int
     */
    public function estimatedVelocity() :int
    {
        return $this->payload()['estimated_velocity'];
    }

    /**
     * @return \DateTimeInterface
     */
    public function startedAt() :\DateTimeInterface
    {
        return new \DateTimeImmutable($this->payload()['started_at']);
    }
}
