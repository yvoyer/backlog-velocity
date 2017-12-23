<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

interface SprintNamingStrategy
{
    /**
     * @param ProjectId $projectId
     *
     * @return SprintName
     */
    public function nextNameOfSprint(ProjectId $projectId) :SprintName;
}
