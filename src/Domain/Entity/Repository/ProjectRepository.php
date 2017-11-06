<?php

namespace Star\Component\Sprint\Domain\Entity\Repository;

use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;

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
