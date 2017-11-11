<?php

namespace Star\Component\Sprint\Domain\Entity\Repository;

use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;

interface ProjectRepository
{
    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws EntityNotFoundException When not found
     */
    public function getProjectWithIdentity(ProjectId $projectId);

    /**
     * @param Project $project
     */
    public function saveProject(Project $project);
}
