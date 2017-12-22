<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\ManDays;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;

final class TeamMemberCommittedToSprint extends AggregateChanged
{
    /**
     * @return SprintId
     */
    public function sprintId()
    {
        return SprintId::fromString($this->aggregateId());
    }

    /**
     * @return MemberId
     */
    public function memberId()
    {
        return MemberId::fromString($this->payload['member_id']);
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
     * @param MemberId $memberId
     * @param ManDays $days
     *
     * @return static
     */
    public static function version1(SprintId $id, MemberId $memberId, ManDays $days)
    {
        return self::occur(
            $id->toString(),
            [
                'member_id' => $memberId->toString(),
                'man_days' => $days->toInt(),
            ]
        );
    }
}
