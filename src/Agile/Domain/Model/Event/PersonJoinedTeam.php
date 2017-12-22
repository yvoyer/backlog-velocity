<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

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
     * @return PersonName
     */
    public function memberName()
    {
        return new PersonName($this->payload['member_name']);
    }

    /**
     * @param ProjectId $projectId
     * @param MemberId $memberId
     * @param PersonName $name
     * @param TeamId $teamId
     *
     * @return static
     */
    public static function version1(
        ProjectId $projectId,
        MemberId $memberId,
        PersonName $name,
        TeamId $teamId
    ) {
        return self::occur(
            $projectId->toString(),
            [
                'member_id' => $memberId->toString(),
                'member_name' => $name->toString(),
                'team_id' => $teamId->toString(),
            ]
        );
    }
}
