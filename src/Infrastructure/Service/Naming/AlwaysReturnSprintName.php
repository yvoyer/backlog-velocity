<?php declare(strict_types=1);

namespace Star\Component\Sprint\Infrastructure\Service\Naming;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Component\Sprint\Domain\Model\SprintNamingStrategy;

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
    public function nextSprintOfProject(ProjectId $projectId): SprintName
    {
        return $this->name;
    }
}
