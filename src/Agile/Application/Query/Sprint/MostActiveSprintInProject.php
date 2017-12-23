<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Query\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Common\Application\Query;

final class MostActiveSprintInProject extends Query
{
    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @param ProjectId $projectId
     */
    public function __construct(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @param string $projectId
     *
     * @return MostActiveSprintInProject
     */
    public static function fromString(string $projectId) :MostActiveSprintInProject
    {
        return new self(ProjectId::fromString($projectId));
    }
}
