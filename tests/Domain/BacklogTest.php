<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\PersonCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\ProjectCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\SprintCollection;
use Star\Component\Sprint\Infrastructure\Persistence\Collection\TeamCollection;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Project;
use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\SprintId;
use Star\Component\Sprint\Domain\Model\PersonName;
use Star\Component\Sprint\Domain\Model\ProjectName;
use Star\Component\Sprint\Domain\Model\SprintName;
use Star\Plugin\InMemory\InMemoryPlugin;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class BacklogTest extends TestCase
{
    /**
     * @var Backlog
     */
    private $backlog;

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

    public function setUp()
    {
        $this->backlog = new Backlog(
            $this->projects = new ProjectCollection(),
            $this->persons = new PersonCollection(),
            $this->teams = new TeamCollection(),
            $this->sprints = new SprintCollection()
        );
    }

    public function test_should_auto_generate_name_on_multiple_call_when_creating_sprint()
    {
        $this->backlog->createProject($projectId = ProjectId::fromString('name'), new ProjectName('name'));
        $sprint1 = $this->backlog->createSprint(SprintId::uuid(), $projectId, new \DateTime());
        $sprint2 = $this->backlog->createSprint(SprintId::uuid(), $projectId, new \DateTime());
        $this->assertSame('Sprint 1', $sprint1->getName()->toString());
        $this->assertSame('Sprint 2', $sprint2->getName()->toString());
    }
    public function test_it_should_create_project()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addProject('Project name')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Project::class, $this->projects->getProjectWithIdentity(ProjectId::fromString('Project name')));
    }

    public function test_it_should_create_person()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->getBacklog()
        ;

        $this->assertCount(3, $this->persons->allRegistered());
        $this->assertInstanceOf(Person::class, $this->persons->personWithName(new PersonName('Person 1')));
    }

    public function test_it_should_create_teams()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addTeam('Team name 1')
            ->getBacklog()
        ;

        $this->assertCount(1, $this->teams->allTeams());
        $this->assertInstanceOf(Team::class, $this->teams->findOneByName('Team name 1'));
    }

    public function test_it_should_create_sprint()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addProject('Project name')
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->addTeam('Team name 1')
            ->createSprint(SprintId::uuid(), 'Project name', new \DateTime()) // name = "Sprint 1"
            ->endBacklog()
        ;

        $this->assertInstanceOf(Sprint::class, $this->sprints->activeSprintOfProject(ProjectId::fromString('Project name')));
    }

    public function test_it_should_start_a_sprint()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addProject('Project name')
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->addTeam('Team name 1')
            ->createSprint($sprintId = SprintId::uuid(), 'Project name', new \DateTime())
            ->commitedMember('Project name', 'Person 1', $manDays = 5)
            ->started(32)
            ->endBacklog()
        ;

        $this->assertInstanceOf(
            Sprint::class, $this->sprints->activeSprintOfProject($projectId = ProjectId::fromString('project-name'))
        );
        $sprint = $this->sprints->sprintWithName($projectId, new SprintName('Sprint 1'));
        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertTrue($sprint->isStarted());
    }

    public function test_it_should_close_a_sprint()
    {
        BacklogBuilder::fromBacklog($this->backlog)
            ->addProject('Project name')
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->addTeam('Team name 1')
            ->createSprint($sprintId = SprintId::uuid(), 'Project name', new \DateTime())
            ->commitedMember('Project name', 'Person 1', $manDays = 5)
            ->started(32)
            ->ended(43)
            ->endBacklog()
        ;

        $this->assertNull($this->sprints->activeSprintOfProject($pId = ProjectId::fromString('project-name')));
        $sprint = $this->sprints->sprintWithName($pId, new SprintName('Sprint 1'));
        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertFalse($sprint->isStarted());
        $this->assertTrue($sprint->isClosed());
        $this->assertSame(32, $sprint->getEstimatedVelocity());
        $this->assertSame(43, $sprint->getActualVelocity());
        $this->assertSame(860, $sprint->getFocusFactor());
        $this->assertCount(1, $sprint->getCommitments());
    }

//todo                ->discardSprint()
// todo                ->archiveSprint() // only end sprint

    public function test_it_should_throw_exception_when_project_not_found()
    {
        $backlog = Backlog::fromPlugin(new InMemoryPlugin());
        $id = ProjectId::fromString('id');
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(EntityNotFoundException::objectWithIdentity($id)->getMessage());
        $backlog->createSprint(SprintId::uuid(), $id, new \DateTime());
    }
}
