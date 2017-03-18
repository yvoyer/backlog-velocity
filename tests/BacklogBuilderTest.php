<?php

namespace Star\Component\Sprint;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\TeamId;

final class BacklogBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_should_create_basic_data_using_events()
    {
        $backlog = BacklogBuilder::create()
            ->addProject('Project name')
            ->addPerson('Person 1')
            ->addPerson('Person 2')
            ->addPerson('Person 3')
            ->addTeam('Team name 1')
            ->createSprint(new \DateTime()) // name = "Sprint 1"
// todo                ->commitMember('Person 1', $manDays = 5)
// todo                ->commitMember('Person 2', 8)
            // end date > created date
// todo                ->startSprint(new \DateTime(), $estimatedVelocity = 10)
//todo                ->discardSprint()
// todo                ->archiveSprint() // only end sprint
// todo                ->endSprint(new \DateTime(), $actualVelocity = 8) // end date > start date
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $this->assertCount(1, $backlog->projects());
        $this->assertContainsOnlyInstancesOf(ProjectId::class, $backlog->projects());

        $this->assertCount(3, $backlog->persons());
        $this->assertContainsOnlyInstancesOf(PersonId::class, $backlog->persons());

        $this->assertCount(1, $backlog->teams());
        $this->assertContainsOnlyInstancesOf(TeamId::class, $backlog->teams());
    }
//            ->joinTeam('member-1', 'team-name-1')
    //          ->joinTeam('member-3', 'team-name-1')
    //        ->createSprint(new \DateTime(), 'team-name-1')
    //      ->getBacklog()
}
