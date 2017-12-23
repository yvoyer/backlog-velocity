<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Common\Application\Command;

final class CreateSprint extends Command
{
    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var TeamId
     */
    private $teamId;

    /**
     * @param SprintId $sprintId
     * @param ProjectId $projectId
     * @param TeamId $teamId
     */
    public function __construct(SprintId $sprintId, ProjectId $projectId, TeamId $teamId)
    {
        $this->sprintId = $sprintId;
        $this->projectId = $projectId;
        $this->teamId = $teamId;
    }

    public function projectId() :ProjectId
    {
        return $this->projectId;
    }

    public function sprintId() :SprintId
    {
        return $this->sprintId;
    }

    public function teamId() :TeamId
    {
        return $this->teamId;
    }

    /**
     * @param string $sprintId
     * @param string $projectId
     * @param string $teamId
     *
     * @return CreateSprint
     */
    public static function fromString(string $sprintId, string $projectId, string $teamId) :self
    {
        return new self(
            SprintId::fromString($sprintId),
            ProjectId::fromString($projectId),
            TeamId::fromString($teamId)
        );
    }
}
