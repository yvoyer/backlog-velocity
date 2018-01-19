<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;

final class StartSprintHandler
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @param SprintRepository $sprints
     */
    public function __construct(SprintRepository $sprints)
    {
        $this->sprints = $sprints;
    }

    public function __invoke(StartSprint $command) :void
    {
        $sprint = $this->sprints->getSprintWithIdentity($command->sprintId());
        $sprint->start($command->plannedVelocity(), new \DateTimeImmutable());

        $this->sprints->saveSprint($sprint);
    }
}
