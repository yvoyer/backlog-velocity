<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query\Sprint;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Query\Query;

final class CountSprintsInProject extends Query
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
}
