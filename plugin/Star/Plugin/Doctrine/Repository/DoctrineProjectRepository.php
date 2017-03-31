<?php

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\Identity\ProjectId;

final class DoctrineProjectRepository extends EntityRepository implements ProjectRepository
{
    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws EntityNotFoundException When not found
     */
    public function getProjectWithIdentity(ProjectId $projectId)
    {
        return $this->find($projectId->toString());
    }

    /**
     * @param Project $project
     */
    public function saveProject(Project $project)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
