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
use Star\Component\Sprint\Model\ManDays;
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
     * @param ProjectId $projectId
     *
     * @return Project
     * @throws \Star\Component\Identity\Exception\EntityNotFoundException
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

        return array_pop($projects);
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
     * @param \DateTimeInterface $createdAt
     *
     * @return SprintModel
     */
    public function createSprint(ProjectId $projectId, \DateTimeInterface $createdAt)
    {
        $project = $this->projectWithId($projectId);
        $sprint = $project->createSprint(SprintId::uuid(), $createdAt);
        $this->sprints[] = $sprint;

        return $sprint;
    }

    /**
     * @param PersonId $id
     * @param ManDays $days
     */
    public function commitMember(PersonId $id, ManDays $days)
    {

    }

    /**
     * @param ProjectId $id
     *
     * @return SprintId[]
     */
    public function sprintsOfProject(ProjectId $id)
    {
        $sprints = array_filter(
            $this->sprints,
            function (Sprint $sprint) use ($id) {
                return $sprint->matchProject($id);
            }
        );

        return array_map(
            function (Sprint $sprint) {
                return $sprint->getId();
            },
            $sprints
        );
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
