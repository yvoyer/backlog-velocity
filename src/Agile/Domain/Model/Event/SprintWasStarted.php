<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;

final class SprintWasStarted extends AggregateChanged
{
    /**
     * @param SprintId $sprintId
     * @param $plannedVelocity
     * @param \DateTimeInterface $startedAt
     *
     * @return SprintWasStarted
     */
    public static function version1(SprintId $sprintId, $plannedVelocity, \DateTimeInterface $startedAt)
    {
        return new self(
            $sprintId->toString(),
            [
                'planned_velocity' => $plannedVelocity,
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
    public function plannedVelocity() :int
    {
        return $this->payload()['planned_velocity'];
    }

    /**
     * @return \DateTimeInterface
     */
    public function startedAt() :\DateTimeInterface
    {
        return new \DateTimeImmutable($this->payload()['started_at']);
    }
}
