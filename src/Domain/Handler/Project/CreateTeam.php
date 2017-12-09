<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Project;

use Star\Component\Sprint\Domain\Handler\Command;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;

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
