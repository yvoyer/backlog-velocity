<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\ManDays;

final class TeamMemberCommitedToSprint extends AggregateChanged
{
    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return SprintId::fromString($this->aggregateId());
    }

    /**
     * @return PersonId
     */
    public function personId()
    {
        return PersonId::fromString($this->payload['person_id']);
    }

    /**
     * @return ManDays
     */
    public function manDays()
    {
        return ManDays::fromInt($this->payload['man_days']);
    }

    /**
     * @param SprintId $id
     * @param PersonId $personId
     * @param ManDays $days
     *
     * @return static
     */
    public static function version1(SprintId $id, PersonId $personId, ManDays $days)
    {
        return self::occur(
            $id->toString(),
            [
                'person_id' => $personId->toString(),
                'man_days' => $days->toInt(),
            ]
        );
    }
}
