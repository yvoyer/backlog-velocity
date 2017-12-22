<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Common\Application\Command;

final class CreateTeam extends Command
{
    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @var TeamName
     */
    private $teamName;

    /**
     * @param TeamId $teamId
     * @param TeamName $teamName
     */
    public function __construct(TeamId $teamId, TeamName $teamName)
    {
        $this->teamId = $teamId;
        $this->teamName = $teamName;
    }

    /**
     * @return TeamId
     */
    public function teamId() :TeamId
    {
        return $this->teamId;
    }

    /**
     * @return TeamName
     */
    public function name() :TeamName
    {
        return $this->teamName;
    }

    /**
     * @param string $teamId
     * @param string $name
     *
     * @return CreateTeam
     */
    public static function fromString(string $teamId, string $name) :self
    {
        return new self(TeamId::fromString($teamId), new TeamName($name));
    }
}
