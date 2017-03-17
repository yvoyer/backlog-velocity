<?php

namespace Star\Component\Sprint;

final class BacklogTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_should_create_project()
    {
        $backlog = BacklogBuilder::create()
            ->addProject('Project name')
            ->addMember('Member 1')
            ->addMember('Member 2')
            ->addMember('Member 3')
            ->addTeam('Team name 1')
            ->getBacklog()
        ;

        $this->assertInstanceOf(Backlog::class, $backlog);
        $backlog->
        $this->fail('assert events with values');
    }
//            ->joinTeam('member-1', 'team-name-1')
    //          ->joinTeam('member-3', 'team-name-1')
    //        ->createSprint(new \DateTime(), 'team-name-1')
    //      ->getBacklog()
}
