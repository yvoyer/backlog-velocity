<?php

namespace Star\Component\Sprint;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectAggregate;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;

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
     * @var Team[]
     */
    private $teams = [];

    /**
     * @var Sprint[]
     */
    private $sprints = [];

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
     * @param PersonId $id
     * @param PersonName $name
     *
     * @return PersonModel
     */
    public function createPerson(PersonId $id, PersonName $name)
    {
        $person = new PersonModel($id, $name);
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
                return $person->getId();
            },
            $this->persons
        );
    }

    /**
     * @param TeamId $id
     * @param TeamName $name
     *
     * @return TeamModel
     */
    public function createTeam(TeamId $id, TeamName $name)
    {
        $team = new TeamModel($id, $name);
        $this->teams[] = $team;

        return $team;
    }

    /**
     * @return TeamId[]
     */
    public function teams()
    {
        return array_map(
            function(Team $team) {
                return $team->getId();
            },
            $this->teams
        );
    }

    /**
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws EntityNotFoundException
     */
    public function projectWithId(ProjectId $projectId)
    {
        $projects = array_filter(
            $this->projects,
            function (Project $project) use ($projectId) {
                return $projectId->matchIdentity($project->getIdentity());
            }
        );
        if (count($projects) == 0) {
            throw EntityNotFoundException::objectWithIdentity($projectId);
        }

//        return array_pop($projects);
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @param ProjectId $projectId
     *
     * @return SprintModel
     */
    public function createSprint(\DateTimeInterface $createdAt, ProjectId $projectId)
    {
        $project = $this->projectWithId($projectId);
        return $project->createSprint(SprintId::uuid(), $createdAt);
        $sprint = new SprintModel(
            new SprintId(),
            'Sprint ' . count($project->sprints()),
            $project,
            $createdAt
        );
//        $this->sprints[] = $sprint;

        return $sprint;
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
