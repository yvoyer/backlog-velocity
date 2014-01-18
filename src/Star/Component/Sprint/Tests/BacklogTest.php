<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Model\Availability;
use Star\Component\Sprint\Model\Backlog;

/**
 * Class BacklogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests
 */
class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Backlog
     */
    private $backlog;

    public function setUp()
    {
        $this->backlog = new Backlog();
    }

    public function testShouldStartASprint()
    {
        $this->backlog->createTeam($teamName = 'team1');
        $this->backlog->createSprint($sprintName = 'sprint1', $teamName);

        $this->backlog->createPerson($personInTeam = 'personInTeam');
        $this->backlog->addTeamMember($teamName, $personInTeam);
        $this->backlog->addSprinter($sprintName, $personInTeam, 10);

        $this->backlog->createPerson($independentPerson = 'independentPerson');
        $this->backlog->addSprinter($sprintName, $independentPerson, 15);

        $this->backlog->createPerson($notLinkedPerson = 'notLinkedPerson'); // todo remove line later, Will create a independent
        $this->backlog->addSprinter($sprintName, $notLinkedPerson, 8);

        $this->assertSame(23, $this->backlog->estimateVelocity($sprintName, new ResourceCalculator($this->backlog)));
        $this->backlog->startSprint($sprintName);

        $sprint = $this->backlog->getSprint($sprintName);
        $this->assertSame($sprintName, $sprint->getName());
        $this->assertSame(33, $sprint->getManDays());
        $this->assertSame(23, $sprint->getEstimatedVelocity());
        $this->assertSame(0, $sprint->getActualVelocity());

// todo       $this->backlog->endSprint($sprintName, 44);
//        $this->assertSame(44, $sprint->getActualVelocity());
    }
}
