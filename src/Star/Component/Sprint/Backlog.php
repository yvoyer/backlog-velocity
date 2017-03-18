<?php

namespace Star\Component\Sprint;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;

final class Backlog extends AggregateRoot
{
    /**
     * @var Project[]
     */
    private $projects = [];

    /**
     * @var Person[]
     */
    private $persons = [];

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectAggregate
     */
    public function createProject(ProjectId $id, ProjectName $name)
    {
        $project = ProjectAggregate::emptyProject($id, $name);;
        $this->projects[] = $project;

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
     * @param string $name
     *
     * @return PersonModel
     */
    public function createPerson($name)
    {
        $person = new PersonModel(PersonId::fromString($name), new PersonName($name));
        $this->persons[] = $person;

        return $person;
    }

    /**
     * @return PersonId[]
     */
    public function persons()
    {
        return array_map(
            function(Person $person) {
                return $person->getIdentity();
            },
            $this->persons
        );
    }

    /**
     * @return TeamId[]
     */
    public function teams()
    {
        return [];
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
