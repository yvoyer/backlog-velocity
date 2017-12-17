<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;

final class SprintWasClosed extends AggregateChanged
{
    /**
     * @param SprintId $sprintId
     * @param int $actualVelocity
     * @param \DateTimeInterface $endedAt
     *
     * @return SprintWasClosed
     */
    public static function version1(SprintId $sprintId, int $actualVelocity, \DateTimeInterface $endedAt)
    {
        return new self(
            $sprintId->toString(),
            [
                'actual_velocity' => $actualVelocity,
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
    public function actualVelocity() :int
    {
        return $this->payload()['actual_velocity'];
    }

    /**
     * @return \DateTimeInterface
     */
    public function endedAt() :\DateTimeInterface
    {
        return new \DateTimeImmutable($this->payload()['ended_at']);
    }
}
