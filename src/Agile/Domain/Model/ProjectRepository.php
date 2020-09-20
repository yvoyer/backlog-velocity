<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;

interface ProjectRepository
{
    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws EntityNotFoundException When not found
     */
    public function getProjectWithIdentity(ProjectId $projectId): Project;

    public function projectExists(ProjectName $name): bool;

    public function saveProject(Project $project): void;
}
