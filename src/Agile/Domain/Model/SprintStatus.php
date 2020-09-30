<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

final class SprintStatus
{
    const PENDING = 'pending';
    const STARTED = 'started';
    const CLOSED = 'closed';

    public static function fromAggregate(Sprint $sprint): string
    {
        if ($sprint->isStarted()) {
            return self::STARTED;
        }

        if ($sprint->isClosed()) {
            return self::CLOSED;
        }

        return self::PENDING;
    }
}
