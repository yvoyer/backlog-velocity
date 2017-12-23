<?php

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection;

use Star\BacklogVelocity\Agile\Application\Query\ProjectDTO;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Project;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\Component\Collection\TypedCollection;

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
