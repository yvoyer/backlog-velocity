<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;

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
        $sprint->start($command->estimatedVelocity(), new \DateTimeImmutable());

        $this->sprints->saveSprint($sprint);
    }
}
