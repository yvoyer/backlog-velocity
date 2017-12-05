<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
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
