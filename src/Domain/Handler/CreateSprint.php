<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;

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
