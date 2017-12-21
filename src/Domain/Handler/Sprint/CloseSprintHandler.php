<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Handler\Sprint;

use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;

final class CloseSprintHandler
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

    public function __invoke(CloseSprint $command)
    {
        $sprint = $this->sprints->getSprintWithIdentity($command->sprintId());
        $sprint->close($command->actualVelocity()->toInt(), new \DateTimeImmutable());

        $this->sprints->saveSprint($sprint);
    }
}
