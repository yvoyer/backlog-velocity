<?php

namespace spec\Star\Component\Sprint;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Star\Component\Sprint\ProjectInterface;
use Star\Component\Sprint\Sprinter;
use Star\Component\Sprint\TeamInterface;
use DateTime;

class BacklogSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Star\Component\Sprint\Backlog');
    }

    function it_should_manage_sprint_creation()
    {
        $startDate = new DateTime();
        $endDate   = new DateTime('+2day');

        $sprint = $this->startSprint(array('Sprinter2', 'Sprinter3'), 'Team2', $startDate, $endDate);
        $sprint->shouldBeAnInstanceOf('Star\Component\Sprint\Sprint');
        $sprint->getSprinters('Team2')->shouldHaveCount(2);
        $sprint->getName()->shouldReturn('Sprint 1');
        $sprint->getStartDate()->shouldReturn($startDate);
        $sprint->getEndDate()->shouldReturn($endDate);

        $team = $sprint->getTeam();
        $team->shouldBeAnInstanceOf('Star\Component\Sprint\Team');
        $team->getName()->shouldReturn('Team2');

        $sprinters = $sprint->getSprinters('Team2');
        $sprinter1 = $sprinters[0];
        $sprinter1->shouldBeAnInstanceOf('Star\Component\Sprint\Sprinter');
        $sprinter1->getName()->shouldReturn('Sprinter1');

        $sprinter2 = $sprinters[1];
        $sprinter2->shouldBeAnInstanceOf('Star\Component\Sprint\Sprinter');
        $sprinter2->getName()->shouldReturn('Sprinter2');
    }
//
//    function it_should_throw_exception_when_sprinter_already_in_sprint()
//    {
//        $this->startSprint(array('Sprinter1'), 'Team1', new DateTime());
//        $this->startSprint(array('Sprinter1'), 'Team2', new DateTime());
//
//    }
}
