<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
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
     * @var TeamRepository
     */
    private $teams;

    /**
     * @var SprintNamingStrategy
     */
    private $strategy;

    /**
     * @param ProjectRepository $projects
     * @param SprintRepository $sprints
     * @param TeamRepository $teams
     * @param SprintNamingStrategy $strategy
     */
    public function __construct(
        ProjectRepository $projects,
        SprintRepository $sprints,
        TeamRepository $teams,
        SprintNamingStrategy $strategy
    ) {
        $this->projects = $projects;
        $this->sprints = $sprints;
        $this->teams = $teams;
        $this->strategy = $strategy;
    }

    /**
     * @param CreateSprint $command
     * @throws EntityNotFoundException
     */
    public function __invoke(CreateSprint $command) :void
    {
        $projectId = $command->projectId();
        $project = $this->projects->getProjectWithIdentity($projectId);
        if (! $this->teams->teamWithIdentityExists($command->teamId())) {
            throw EntityNotFoundException::objectWithIdentity($command->teamId());
        }

        $sprint = $project->createSprint(
            $command->sprintId(),
            $this->strategy->nextNameOfSprint($projectId),
            $command->teamId(),
            new \DateTimeImmutable()
        );

        $this->sprints->saveSprint($sprint);
    }
}
