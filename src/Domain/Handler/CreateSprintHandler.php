<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Model\SprintNamingStrategy;

final class CreateSprintHandler
{
    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @var SprintNamingStrategy
     */
    private $strategy;

    /**
     * @param ProjectRepository $projects
     * @param SprintRepository $sprints
     * @param SprintNamingStrategy $strategy
     */
    public function __construct(ProjectRepository $projects, SprintRepository $sprints, SprintNamingStrategy $strategy)
    {
        $this->projects = $projects;
        $this->sprints = $sprints;
        $this->strategy = $strategy;
    }

    /**
     * @param CreateSprint $command
     */
    public function __invoke(CreateSprint $command) :void
    {
        $projectId = $command->projectId();
        $project = $this->projects->getProjectWithIdentity($projectId);

        $sprint = $project->createSprint(
            $command->sprintId(),
            $this->strategy->nextNameOfSprint($projectId),
            new \DateTimeImmutable()
        );

        $this->sprints->saveSprint($sprint);
    }
}
