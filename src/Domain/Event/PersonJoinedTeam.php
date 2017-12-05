<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

final class PersonJoinedTeam extends AggregateChanged
{
    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return ProjectId::fromString($this->payload['project_id']);
    }

    /**
     * @return MemberId
     */
    public function memberId()
    {
        return MemberId::fromString($this->payload['member_id']);
    }

    /**
     * @return TeamId
     */
    public function teamId()
    {
        return TeamId::fromString($this->payload['team_id']);
    }

    /**
     * @param ProjectId $projectId
     * @param MemberId $memberId
     * @param TeamId $teamId
     *
     * @return static
     */
    public static function version1(
        ProjectId $projectId,
        MemberId $memberId,
        TeamId $teamId
    ) {
        return self::occur(
            $projectId->toString(),
            [
                'member_id' => $memberId->toString(),
                'team_id' => $teamId->toString(),
            ]
        );
    }
}
