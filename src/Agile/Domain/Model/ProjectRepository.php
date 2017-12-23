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
    public function getProjectWithIdentity(ProjectId $projectId) :Project;

    /**
     * @param ProjectName $name
     *
     * @return bool
     */
    public function projectExists(ProjectName $name) :bool;

    /**
     * @param Project $project
     */
    public function saveProject(Project $project) :void;
}
