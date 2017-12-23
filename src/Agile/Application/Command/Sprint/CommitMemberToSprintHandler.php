<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;

final class CommitMemberToSprintHandler
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

    public function __invoke(CommitMemberToSprint $command)
    {
        $sprint = $this->sprints->getSprintWithIdentity($command->sprintId());
        $sprint->commit($command->memberId(), $command->manDays());

        $this->sprints->saveSprint($sprint);
    }
}
