<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Naming;

use Prooph\ServiceBus\QueryBus;
use Star\BacklogVelocity\Agile\Application\Query\Sprint\CountSprintsInProject;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintNamingStrategy;

final class AutoIncrementName implements SprintNamingStrategy
{
    /**
     * @var QueryBus
     */
    private $bus;

    /**
     * @param QueryBus $bus
     */
    public function __construct(QueryBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ProjectId $projectId
     *
     * @return SprintName
     */
    public function nextNameOfSprint(ProjectId $projectId): SprintName
    {
        $promise = $this->bus->dispatch(new CountSprintsInProject($projectId));
        $sprintCount = 0;
        $promise->done(
            function ($value) use (&$sprintCount) {
                $sprintCount = $value;
            }
        );

        return new SprintName('Sprint ' . ($sprintCount + 1));
    }
}
