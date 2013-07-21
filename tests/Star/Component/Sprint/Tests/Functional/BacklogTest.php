<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Functional;

use Star\Component\Sprint\Backlog;
use Star\Component\Sprint\Tests\Stub\Sprint1;
use Star\Component\Sprint\Tests\Stub\Sprint2;
use Star\Component\Sprint\Tests\Stub\Sprint3;

/**
 * Class BacklogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Functional
 */
class BacklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Backlog
     */
    public function testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable()
    {
        $backlog = new Backlog();
        $availableManDays = 50;
        $this->assertSame(35, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateUsingTheBaseFocusWhenNoStatsAvailable
     *
     * @param Backlog $backlog
     *
     * @return \Star\Component\Sprint\Backlog
     */
    public function testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint1());
        $this->assertSame(25, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateTheSecondSprintBasedOnFirstSprintActualVelocity
     *
     * @param Backlog $backlog
     *
     * @return \Star\Component\Sprint\Backlog
     */
    public function testShouldCalculateTheThirdSprintBasedOnTwoPastSprint(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint2());
        $this->assertSame(32, $backlog->calculateEstimatedVelocity($availableManDays));

        return $backlog;
    }

    /**
     * @depends testShouldCalculateTheThirdSprintBasedOnTwoPastSprint
     *
     * @param Backlog $backlog
     */
    public function testShouldCalculateTheFourthSprintBasedOnThreePastSprint(Backlog $backlog)
    {
        $availableManDays = 50;
        $backlog->addSprint(new Sprint3());
        $this->assertSame(33, $backlog->calculateEstimatedVelocity($availableManDays));
    }
}
