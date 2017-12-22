<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintNamingStrategy;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;

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
