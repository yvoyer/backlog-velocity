<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\Backlog;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class BacklogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 */
class BacklogTest extends UnitTestCase
{
    /**
     * @var Team
     */
    private $empire;

    public function setUp()
    {
        $vador = new PersonModel('Darth Vador');
        $palpatine = new PersonModel('Emperor Palpatine');
        $tk421 = new PersonModel('TK-421');

        $this->empire = new TeamModel('The Empire');
        $this->empire->addMember($vador);
        $this->empire->addMember($palpatine);
        $this->empire->addMember($tk421);
    }

    /**
     * @param $sprintName
     * @param $beforeVelocity
     * @param $actualVelocity
     * @param $finalVelocity
     *
     * @dataProvider provideSprintData
     */
    public function testShouldStartASprint($sprintName, $beforeVelocity, $actualVelocity, $finalVelocity)
    {
        $calculator = new ResourceCalculator();
        $this->assertSame($beforeVelocity, $this->empire->getActualVelocity());
        $sprint = $this->empire->startSprint($sprintName, $calculator);
        $this->assertInstanceOfSprint($sprint);
        // todo manager end of sprint
        //$this->empire->endSprint($actualVelocity);

        $this->assertSame($finalVelocity, $this->empire->getActualVelocity());
    }

    public function provideSprintData()
    {
        return array(
            array('Blow up Alderaan', 0, -1, 35),
            array('Crush the rebel Alliance', 35, -1, 25),
            array('Capture Luke', 25, -1, 32),
            array('Invade Hoth', 32, -1, 33),
        );
    }
}
