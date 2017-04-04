<?php

namespace Star\Component\Sprint\Model;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;

// todo put final remove from entity
class ProjectAggregate extends AggregateRoot implements Project
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
     * @var Sprint[]
     */
    private $sprints = [];

    /**
     * @return ProjectId
     */
    public function getIdentity()
    {
        return ProjectId::fromString($this->aggregateId());
    }

    /**
     * @param SprintId $sprintId
     * @param SprintName $name
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(SprintId $sprintId, SprintName $name, \DateTimeInterface $createdAt)
    {
        $sprint = SprintModel::notStartedSprint($sprintId, $name, $this->getIdentity(), $createdAt);
        $this->sprints[] = $sprint;

        return $sprint;
    }

    /**
     * @return SprintName
     */
    public function nextName()
    {
        return new SprintName('Sprint ' . strval(count($this->sprints) + 1));
    }

    /**
     * @return DomainEvent[]
     */
    public function uncommittedEvents()
    {
        return $this->popRecordedEvents();
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

        return $project;
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->id;
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
