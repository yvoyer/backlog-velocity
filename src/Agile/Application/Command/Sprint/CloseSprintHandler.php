<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;

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
        $sprint->close($command->actualVelocity(), new \DateTimeImmutable());

        $this->sprints->saveSprint($sprint);
    }
}
