<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\Velocity;

final class SprintWasClosed extends AggregateChanged
{
    public static function version1(
        SprintId $sprintId,
        Velocity $actualVelocity,
        \DateTimeInterface $endedAt
    ): self {
        return new self(
            $sprintId->toString(),
            [
                'actual_velocity' => $actualVelocity->toInt(),
                'ended_at' => $endedAt->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return SprintId::fromString($this->aggregateId());
    }

    /**
     * @return int
     */
    public function actualVelocity(): int
    {
        return $this->payload()['actual_velocity'];
    }

    /**
     * @return \DateTimeInterface
     */
    public function endedAt(): \DateTimeInterface
    {
        return new \DateTimeImmutable($this->payload()['ended_at']);
    }
}
