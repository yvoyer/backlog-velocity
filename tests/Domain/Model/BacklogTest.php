<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Rhumsaa\Uuid\Uuid;
use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\BacklogBuilder;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\Identity\TeamId;
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

    public function setUp()
    {
        $this->backlog = new Backlog(

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
        $backlog = BacklogBuilder::create()
            ->addProject('Project name')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertInstanceOf(Project::class, $backlog->projectWithId(ProjectId::fromString('Project name')));
    }

    public function test_it_should_create_person()
    {
        $backlog = BacklogBuilder::create()
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(3, $backlog->persons());
        $this->assertContainsOnlyInstancesOf(PersonId::class, $backlog->persons());
    }

    public function test_it_should_create_teams()
    {
        $backlog = BacklogBuilder::create()
            ->addTeam('Team name 1')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(1, $backlog->teams());
        $this->assertContainsOnlyInstancesOf(TeamId::class, $backlog->teams());
    }

    public function test_it_should_create_sprint()
    {
        $backlog = BacklogBuilder::create()
            ->addProject('Project name')
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->addTeam('Team name 1')
            ->createSprint('Project name', new \DateTime()) // name = "Sprint 1"
            ->endBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(0, $backlog->sprintsOfProject(ProjectId::fromString('none')));
        $this->assertCount(1, $sprints = $backlog->sprintsOfProject(ProjectId::fromString('Project name')));
        $this->assertContainsOnlyInstancesOf(SprintId::class, $sprints);
    }

    public function test_it_should_start_a_sprint()
    {
        $backlog = BacklogBuilder::create()
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

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(1, $sprints = $backlog->sprintsOfProject(ProjectId::fromString('project-name')));
        $this->assertContainsOnlyInstancesOf(SprintId::class, $sprints);
        $this->assertTrue(Uuid::isValid($sprints[0]->toString()), 'Sprint id must be a valid uuid');
    }
//            ->joinTeam('member-1', 'team-name-1')
    //          ->joinTeam('member-3', 'team-name-1')
    //        ->createSprint(new \DateTime(), 'team-name-1')
    //      ->getBacklog()

    public function test_it_should_throw_exception_when_project_not_found()
    {
        $backlog = Backlog::fromPlugin(new InMemoryPlugin());
        $id = ProjectId::fromString('id');
        $this->setExpectedException(
            EntityNotFoundException::class,
            EntityNotFoundException::objectWithIdentity($id)->getMessage()
        );
        $backlog->projectWithId($id);
    }
}
