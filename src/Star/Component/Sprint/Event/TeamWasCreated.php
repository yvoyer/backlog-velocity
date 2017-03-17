<?php

namespace Star\Component\Sprint\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\TeamName;

final class TeamWasCreated extends AggregateChanged
{
    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @var TeamName
     */
    private $name;

    /**
     * @param TeamId $teamId
     * @param TeamName $name
     */
    private function __construct(TeamId $teamId, TeamName $name)
    {
        $this->teamId = $teamId;
        $this->name = $name;
    }

    /**
     * @param string $name
     *
     * @return TeamWasCreated
     */
    public static function version1($name)
    {
        return new self(TeamId::fromString($name), new TeamName($name));
    }
}
