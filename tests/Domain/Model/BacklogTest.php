<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\BacklogBuilder;
use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\ProjectCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Collection\TeamCollection;
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
use Star\Component\Sprint\Model\ProjectName;
use Star\Plugin\InMemory\InMemoryPlugin;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class BacklogTest extends \PHPUnit_Framework_TestCase
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
        $sprint1 = $this->backlog->createSprint($projectId, new \DateTime());
        $sprint2 = $this->backlog->createSprint($projectId, new \DateTime());
        $this->assertSame('Sprint 1', $sprint1->getName());
        $this->assertSame('Sprint 2', $sprint2->getName());
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
        $this->assertInstanceOf(Person::class, $this->persons->findOneById(PersonId::fromString('Person 1')));
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
            ->createSprint('Project name', new \DateTime()) // name = "Sprint 1"
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
            ->createSprint('Project name', new \DateTime())
            ->commitedMember('Project name', 'Person 1', $manDays = 5)
// todo                ->commitMember('Person 2', 8)
            // end date > created date
// todo                ->startSprint(new \DateTime(), $estimatedVelocity = 10)
//todo                ->discardSprint()
// todo                ->archiveSprint() // only end sprint
// todo                ->endSprint(new \DateTime(), $actualVelocity = 8) // end date > start date
            ->started(33)
            ->endBacklog()
        ;

        $sprint = $this->sprints->activeSprintOfProject(ProjectId::fromString('project-name'));
        $this->assertTrue($sprint->isStarted());
    }

    public function test_it_should_throw_exception_when_project_not_found()
    {
        $backlog = Backlog::fromPlugin(new InMemoryPlugin());
        $id = ProjectId::fromString('id');
        $this->setExpectedException(
            EntityNotFoundException::class,
            EntityNotFoundException::objectWithIdentity($id)->getMessage()
        );
        $backlog->createSprint($id, new \DateTime());
    }
}
