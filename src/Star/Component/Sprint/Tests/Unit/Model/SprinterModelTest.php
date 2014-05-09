<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Model;

use Star\Component\Sprint\Model\SprinterModel;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class SprinterModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Model
 */
class SprinterModelTest extends UnitTestCase
{
    /**
     * @var SprinterModel
     */
    private $sprinter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $sprint;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $person;

    public function setUp()
    {
        $this->sprint = $this->getMockSprint();
        $this->sprint
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('sprintId'));

        $this->person = $this->getMockPerson();
        $this->person
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('personId'));

        $this->sprinter = new SprinterModel($this->sprint, $this->person, 4);
    }

    public function testShouldBeSprintMember()
    {
        $this->assertInstanceOfSprintMember($this->sprinter);
    }

    public function testShouldReturnTheAvailableManDays()
    {
        $this->assertSame(4, $this->sprinter->getAvailableManDays());
    }

    public function testShouldReturnId()
    {
        $this->assertSame('sprintId_personId', (string) $this->sprinter->getId());
    }
}
 