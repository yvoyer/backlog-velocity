<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;

final class TeamWasCreated extends AggregateChanged
{
    /**
     * @return TeamId
     */
    public function teamId()
    {
        return TeamId::fromString($this->aggregateId());
    }

    /**
     * @return TeamName
     */
    public function name()
    {
        return new TeamName($this->payload['name']);
    }

    /**
     * @param TeamId $teamId
     * @param TeamName $teamName
     *
     * @return static
     */
    public static function version1(TeamId $teamId, TeamName $teamName)
    {
        return self::occur(
            $teamId->toString(),
            [
                'name' => $teamName->toString(),
            ]
        );
    }
}
