<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\SprintModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprintModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 *
 * @covers Star\Component\Sprint\Model\SprintModel
 */
class SprintModelTest extends UnitTestCase
{
    /**
     * @var SprintModel
     */
    private $sut;

    /**
     * @var Team|\PHPUnit_Framework_MockObject_MockObject
     */
    private $team;

    public function setUp()
    {
        $this->team = $this->getMockTeam();
        $this->sut = new SprintModel('name', $this->team);
    }

    public function testShouldReturnTheName()
    {
        $this->assertSame('name', $this->sut->getName());
    }
}
 