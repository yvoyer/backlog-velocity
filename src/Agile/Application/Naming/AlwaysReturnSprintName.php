<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Naming;

use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintNamingStrategy;

final class AlwaysReturnSprintName implements SprintNamingStrategy
{
    /**
     * @var SprintName
     */
    private $name;

    /**
     * @param SprintName $name
     */
    public function __construct(SprintName $name)
    {
        $this->name = $name;
    }

    /**
     * @param ProjectId $projectId
     *
     * @return SprintName
     */
    public function nextNameOfSprint(ProjectId $projectId): SprintName
    {
        return $this->name;
    }
}
