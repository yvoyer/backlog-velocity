<?php

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\Identity\ProjectId;

final class ProjectCollection implements ProjectRepository
{
    /**
     * @var TypedCollection|Project[]
     */
    private $projects;

    /**
     * @param Project[] $projects
     */
    public function __construct(array $projects = [])
    {
        $this->projects = new TypedCollection(Project::class, $projects);
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws EntityNotFoundException
     */
    public function getProjectWithIdentity(ProjectId $projectId)
    {
        $project = $this->projects->filter(function (Project $p) use ($projectId) {
            return $projectId->matchIdentity($p->getIdentity());
        })->first();
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
        $this->projects[] = $project;
    }
}
