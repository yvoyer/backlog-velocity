<?php

namespace Star\Component\Sprint\Domain;

use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\ManDays;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\PersonName;
use Star\Component\Sprint\Domain\Model\ProjectAggregate;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintModel;
use Star\Component\Sprint\Domain\Model\TeamModel;
use Star\Component\Sprint\Domain\Model\TeamName;

/**
 * @deprecated todo remove
 */
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
     * @param SprintId $id
     * @param ProjectId $projectId
     * @param \DateTimeInterface $createdAt
     *
     * @return SprintModel
     */
    public function createSprint(SprintId $id, ProjectId $projectId, \DateTimeInterface $createdAt)
    {
        $project = $this->projects->getProjectWithIdentity($projectId);
        $sprint = $project->createSprint($id, $project->nextName(), $createdAt);

        $this->sprints->saveSprint($sprint);

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
        $sprint = $this->sprints->activeSprintOfProject($projectId);
        $sprint->commit($id, $days);

        $this->sprints->saveSprint($sprint);
    }

    /**
     * @return ProjectRepository
     */
    public function projects()
    {
        return $this->projects;
    }

    /**
     * @return PersonRepository
     */
    public function persons()
    {
        return $this->persons;
    }

    /**
     * @return TeamRepository
     */
    public function teams()
    {
        return $this->teams;
    }

    /**
     * @return SprintRepository
     */
    public function sprints()
    {
        return $this->sprints;
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
}
