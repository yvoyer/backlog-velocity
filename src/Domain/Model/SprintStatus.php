<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Entity\Sprint;

final class SprintStatus
{
    const PENDING = 'pending';
    const STARTED = 'started';
    const CLOSED = 'closed';

    /**
     * @param Sprint $sprint
     *
     * @return string
     */
    public static function fromAggregate(Sprint $sprint) :string
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
