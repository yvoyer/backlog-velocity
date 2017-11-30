<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;

final class CreateSprint extends Command
{
    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var SprintId
     */
    private $sprintId;

    /**
     * @param ProjectId $projectId
     * @param SprintId $sprintId
     */
    public function __construct(ProjectId $projectId, SprintId $sprintId)
    {
        $this->projectId = $projectId;
        $this->sprintId = $sprintId;
    }

    public function projectId() :ProjectId
    {
        return $this->projectId;
    }

    public function sprintId() :SprintId
    {
        return $this->sprintId;
    }

    /**
     * @param string $projectId
     * @param string $sprintId
     *
     * @return CreateSprint
     */
    public static function fromString(string $projectId, string $sprintId) :self
    {
        return new self(ProjectId::fromString($projectId), SprintId::fromString($sprintId));
    }
}
