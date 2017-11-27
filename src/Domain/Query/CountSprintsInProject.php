<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Query;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;

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
