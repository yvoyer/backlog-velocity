<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Service\Naming;

use Prooph\ServiceBus\QueryBus;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\SprintNamingStrategy;
use Star\Component\Sprint\Domain\Query\SprintsOfProject;

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
    public function nextSprintOfProject(ProjectId $projectId): SprintName
    {
        $promise = $this->bus->dispatch(new SprintsOfProject($projectId));
        $sprintCount = 0;
        $promise->done(
            function ($value) use (&$sprintCount) {
                $sprintCount = $value;
            }
        );

        return new SprintName('Sprint ' . $sprintCount);
    }
}
