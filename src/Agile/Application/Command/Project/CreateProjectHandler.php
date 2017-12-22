<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;

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
