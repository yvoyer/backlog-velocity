<?php

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\Identity\ProjectId;

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
