<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;

interface SprintNamingStrategy
{
    /**
     * @param ProjectId $projectId
     *
     * @return SprintName
     */
    public function nextNameOfSprint(ProjectId $projectId) :SprintName;
}
