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
     * @throws \Star\Component\Identity\Exception\EntityNotFoundException
     */
    public function getProjectWithIdentity(ProjectId $projectId)
    {
        $project = $this->find($projectId->toString());
        if (! $project) {
            throw EntityNotFoundException::objectWithIdentity($projectId);
        }

        return $project;
    }

    /**
     * @param Project $project
     */
    public function saveProject(Project $project)
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }
}
