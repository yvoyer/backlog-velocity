<?php

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;

final class CreateProjectHandler
{
    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @param ProjectRepository $projects
     */
    public function __construct(ProjectRepository $projects)
    {
        $this->projects = $projects;
    }

    /**
     * @param CreateProject $command
     * @throws EntityAlreadyExistsException
     */
    public function __invoke(CreateProject $command) :void
    {
        if ($this->projects->projectExists($command->name())) {
            throw EntityAlreadyExistsException::withAttribute($command->projectId(), $command->name());
        }

        $project = ProjectAggregate::emptyProject($command->projectId(), $command->name());
        $this->projects->saveProject($project);
    }
}
