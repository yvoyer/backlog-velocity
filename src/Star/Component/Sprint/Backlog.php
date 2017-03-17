<?php

namespace Star\Component\Sprint;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Event\ProjectWasCreated;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;

final class Backlog extends AggregateRoot
{
    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return Project
     */
    public function createProject(ProjectId $id, ProjectName $name)
    {
        return ProjectAggregate::emptyProject($id, $name);
    }

    /**
     * @return ProjectId[]
     */
    public function projects()
    {
        return [];
    }

    /**
     * @return PersonId[]
     */
    public function members()
    {
        return [];
    }

    /**
     * @return TeamId[]
     */
    public function teams()
    {
        return [];
    }

    /**
     * @return DomainEvent[]
     */
    public function uncommittedEvents()
    {
        return $this->popRecordedEvents();
    }

    /**
     * @return Backlog
     */
    public static function emptyBacklog()
    {
        return self::fromArray([]);
    }

    /**
     * @param AggregateChanged[] $events
     *
     * @return static
     */
    public static function fromArray(array $events)
    {
        return self::reconstituteFromHistory(new \ArrayIterator($events));
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
