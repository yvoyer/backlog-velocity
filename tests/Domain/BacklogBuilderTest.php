<?php

namespace Star\Component\Sprint\Domain;

use Star\Component\Identity\Exception\EntityNotFoundException;
use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\BacklogBuilder;
use Star\Component\Sprint\Entity\Project;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\Identity\TeamId;

final class BacklogBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_should_create_project()
    {
        $backlog = BacklogBuilder::create()
            ->addProject('Project name')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(1, $backlog->projects());
        $this->assertContainsOnlyInstancesOf(ProjectId::class, $backlog->projects());
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
            ->createSprint(ProjectId::fromString('Project name'), new \DateTime()) // name = "Sprint 1"
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
            ->createSprint(ProjectId::fromString('Project name'), new \DateTime())
            ->commitedMember('Person 1', $manDays = 5)
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
        $startedSprint = $backlog->startedSprint(ProjectId::fromString('project-name'));
        $this->assertInstanceOf(Sprint::class, $startedSprint);
        $this->assertSame('', $startedSprint->isStarted());
        $this->assertSame('', $startedSprint->startedAt());
        $this->assertSame('', $startedSprint->estimatedVelocity());
        $this->assertSame('', $startedSprint->isStarted());
    }
//            ->joinTeam('member-1', 'team-name-1')
    //          ->joinTeam('member-3', 'team-name-1')
    //        ->createSprint(new \DateTime(), 'team-name-1')
    //      ->getBacklog()

    public function test_it_should_throw_exception_when_project_not_found()
    {
        $backlog = Backlog::emptyBacklog();
        $id = ProjectId::fromString('id');
        $this->setExpectedException(
            EntityNotFoundException::class,
            EntityNotFoundException::objectWithIdentity($id)->getMessage()
        );
        $backlog->projectWithId($id);
    }
}
