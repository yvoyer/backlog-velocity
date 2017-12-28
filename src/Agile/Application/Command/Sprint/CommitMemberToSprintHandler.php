<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Sprint;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;

final class CommitMemberToSprintHandler
{
    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @var PersonRepository
     */
    private $persons;

    /**
     * @param SprintRepository $sprints
     * @param PersonRepository $persons
     */
    public function __construct(SprintRepository $sprints, PersonRepository $persons)
    {
        $this->sprints = $sprints;
        $this->persons = $persons;
    }

    public function __invoke(CommitMemberToSprint $command)
    {
        $sprint = $this->sprints->getSprintWithIdentity($command->sprintId());
        if (! $this->persons->personWithIdExists(PersonId::fromString($command->memberId()->toString()))) {
            throw EntityNotFoundException::objectWithIdentity($command->memberId());
        }

        $sprint->commit($command->memberId(), $command->manDays());

        $this->sprints->saveSprint($sprint);
    }
}
