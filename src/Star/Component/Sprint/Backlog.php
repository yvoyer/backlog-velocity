<?php

namespace Star\Component\Sprint;

use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;

final class Backlog extends AggregateRoot
{
    /**
     * @var Project[]
     */
    private $projects = [];

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return Project
     */
    public function createProject(ProjectId $id, ProjectName $name)
    {
        $project = ProjectAggregate::emptyProject($id, $name);;
        $this->addProject($project);

        return $project;
    }

    /**
     * @return ProjectId[]
     */
    public function projects()
    {
        return array_map(
            function (Project $project) {
                return $project->getIdentity();
            },
            $this->projects
        );
    }

    /**
     * @return PersonId[]
     */
    public function persons()
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

    /**
     * @param Project $project
     */
    private function addProject(Project $project)
    {
        $this->projects[] = $project;
    }
}
