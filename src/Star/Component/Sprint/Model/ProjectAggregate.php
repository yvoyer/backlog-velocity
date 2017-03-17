<?php

namespace Star\Component\Sprint\Model;

use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Model\Identity\ProjectId;

final class ProjectAggregate extends AggregateRoot implements Project
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectAggregate
     */
    public static function emptyProject(ProjectId $id, ProjectName $name)
    {
        $project = new self();
        $project->recordThat(ProjectWasCreated::version1($id, $name));
    }

    /**
     * @param ProjectWasCreated $event
     */
    protected function whenProjectWasCreated(ProjectWasCreated $event)
    {
        $this->id = $event->projectId()->toString();
        $this->name = $event->projectName()->toString();
    }
}
