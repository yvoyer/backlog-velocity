<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Command\Project;

use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectName;
use Star\BacklogVelocity\Common\Application\Command;

final class CreateProject extends Command
{
    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var ProjectName
     */
    private $name;

    /**
     * @param ProjectId $projectId
     * @param ProjectName $name
     */
    public function __construct(ProjectId $projectId, ProjectName $name)
    {
        $this->projectId = $projectId;
        $this->name = $name;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @return ProjectName
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $projectId
     * @param string $name
     *
     * @return CreateProject
     */
    public static function fromString(string $projectId, string $name) :self
    {
        return new self(ProjectId::fromString($projectId), new ProjectName($name));
    }
}
