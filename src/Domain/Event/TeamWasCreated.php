<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;

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
