<?php

namespace Star\Component\Sprint\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\ProjectName;

final class ProjectWasCreated extends AggregateChanged
{
    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return ProjectId::fromString($this->payload['project_id']);
    }

    /**
     * @return ProjectName
     */
    public function projectName()
    {
        return new ProjectName($this->payload['project_name']);
    }

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectWasCreated
     */
    public static function version1(ProjectId $id, ProjectName $name)
    {
        return $event = new self(
            $id->toString(),
            [
                'name' => $name->toString(),
                'project_id' => $id->toString(),
                'project_name' => $name->toString(),
            ]
        );
    }
}
