<?php

namespace Star\Component\Sprint;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
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
use Star\Component\Sprint\Model\TeamMemberModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\Plugin\BacklogPlugin;

final class Backlog
{
    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @var PersonRepository
     */
    private $persons;

    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @var SprintRepository
     */
    private $sprints;

    /**
     * @param ProjectRepository $projects
     * @param PersonRepository $persons
     * @param TeamRepository $teams
     * @param SprintRepository $sprints
     */
    public function __construct(
        ProjectRepository $projects,
        PersonRepository $persons,
        TeamRepository $teams,
        SprintRepository $sprints
    ) {
        $this->projects = $projects;
        $this->persons = $persons;
        $this->teams = $teams;
        $this->sprints = $sprints;
    }

    /**
     * @param ProjectId $id
     * @param ProjectName $name
     *
     * @return ProjectAggregate
     */
    public function createProject(ProjectId $id, ProjectName $name)
    {
        $project = ProjectAggregate::emptyProject($id, $name);;
        $this->projects->saveProject($project);

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
        return $this->projects->getProjectWithIdentity($projectId);
//        $projects = array_filter(
//            $this->projects,
//            function (Project $project) use ($projectId) {
//                return $projectId->matchIdentity($project->getIdentity());
//            }
//        );
//        if (count($projects) == 0) {
//            throw EntityNotFoundException::objectWithIdentity($projectId);
//        }
//
//        return array_pop($projects);
    }
//
//    /**
//     * @return ProjectId[]
//     */
//    public function projects()
//    {
//        return $this->projects->
//        return array_map(
//            function (Project $project) {
//                return $project->getIdentity();
//            },
//            $this->projects
//        );
//    }

    /**
     * @param PersonId $id
     * @param PersonName $name
     *
     * @return PersonModel
     */
    public function createPerson(PersonId $id, PersonName $name)
    {
        $person = new PersonModel($id, $name);
        $this->persons->savePerson($person);

        return $person;
    }
//
//    /**
//     * @return PersonId[]
//     */
//    public function persons()
//    {
//        return array_map(
//            function(Person $person) {
//                return $person->getId();
//            },
//            $this->persons
//        );
//    }

    /**
     * @param TeamId $id
     * @param TeamName $name
     *
     * @return TeamModel
     */
    public function createTeam(TeamId $id, TeamName $name)
    {
        $team = new TeamModel($id, $name);
        $this->teams->saveTeam($team);

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
     * @param ProjectId $projectId
     * @param PersonId $id
     * @param ManDays $days
     * @throws EntityNotFoundException
     */
    public function commitMember(ProjectId $projectId, PersonId $id, ManDays $days)
    {
        // todo fetch actual active sprint of project (there can be only one)
        $sprint = $this->activeSprintOfProject($projectId);
        $sprint->commit($id, $days);

    }

    /**
     * @param ProjectId $projectId
     *
     * @return Sprint
     * @throws EntityNotFoundException
     */
    private function activeSprintOfProject(ProjectId $projectId)
    {
        $sprints = array_filter($this->sprints, function (Sprint $sprint) use ($projectId) {
            return $sprint->matchProject($projectId);
        });

        if (count($sprints) > 1) {
            throw new \LogicException('Cannot have more than one sprint for a project.');
        }

        if (count($sprints) != 1) {
            throw new EntityNotFoundException("No active sprint was found for project '{$projectId->toString()}'.");
        }

        return array_pop($sprints);
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
     * @param BacklogPlugin $plugin
     *
     * @return Backlog
     */
    public static function fromPlugin(BacklogPlugin $plugin)
    {
        return new self(
            $plugin->getRepositoryManager()->getProjectRepository(),
            $plugin->getRepositoryManager()->getPersonRepository(),
            $plugin->getRepositoryManager()->getTeamRepository(),
            $plugin->getRepositoryManager()->getSprintRepository()
        );
    }
//    /**
//     * @return Backlog
//     */
//    public static function emptyBacklog()
//    {
//        return self::fromArray([]);
//    }
//
//    /**
//     * @param AggregateChanged[] $events
//     *
//     * @return static
//     */
//    public static function fromArray(array $events)
//    {
//        return self::reconstituteFromHistory(new \ArrayIterator($events));
//    }

//    /**
//     * @return string representation of the unique identifier of the aggregate root
//     */
//    protected function aggregateId()
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }
}
