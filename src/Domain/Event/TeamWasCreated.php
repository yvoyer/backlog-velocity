<?php

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;

final class TeamWasCreated extends AggregateChanged
{
    /**
     * @return ProjectId
     */
    public function projecId()
    {
        return ProjectId::fromString($this->aggregateId());
    }

    /**
     * @return TeamId
     */
    public function teamId()
    {
        return TeamId::fromString($this->payload['team_id']);
    }

    /**
     * @return TeamName
     */
    public function name()
    {
        return new TeamName($this->payload['name']);
    }

    /**
     * @param ProjectId $projectId
     * @param TeamId $teamId
     * @param TeamName $teamName
     *
     * @return static
     */
    public static function version1(ProjectId $projectId, TeamId $teamId, TeamName $teamName)
    {
        return self::occur(
            $projectId->toString(),
            [
                'team_id' => $teamId->toString(),
                'name' => $teamName->toString(),
            ]
        );
    }
}
