<?php

namespace Star\Component\Sprint\Domain\Handler;

use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;

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
