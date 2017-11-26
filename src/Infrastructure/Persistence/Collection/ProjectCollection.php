<?php

namespace Star\Component\Sprint\Infrastructure\Persistence\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Port\ProjectDTO;
use Star\Component\Sprint\Domain\Projections\AllProjectsProjection;

final class ProjectCollection implements ProjectRepository, AllProjectsProjection
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
     * @return Project
     * @throws EntityNotFoundException
     */
    public function getProjectWithIdentity(ProjectId $projectId) :Project
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
    public function saveProject(Project $project) :void
    {
        $this->projects[] = $project;
    }

    /**
     * @param ProjectName $name
     *
     * @return bool
     */
    public function projectExists(ProjectName $name): bool
    {
        return $this->projects->exists(function ($key, Project $_p) use ($name) {
            return $name->equalsTo($_p->name());
        });
    }

    /**
     * @return ProjectDTO[]
     */
    public function allProjects()
    {
        return array_map(
            function (ProjectAggregate $aggregate) {
                return new ProjectDTO($aggregate->getIdentity(), $aggregate->name());
            },
            $this->projects->getValues()
        );
    }
}
