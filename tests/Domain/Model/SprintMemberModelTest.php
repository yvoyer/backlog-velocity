<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\SprintCommitment;
use tests\UnitTestCase;

/**
 * Class SprintMemberModelTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @covers Star\Component\Sprint\Model\SprintMemberModel
 */
class SprintMemberModelTest extends UnitTestCase
{
    /**
     * @var SprintCommitment
     */
    private $commitment;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Sprint
     */
    private $sprint;

    public function setUp()
    {
        $this->sprint = $this->getMockSprint();
        $this->sprint
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('sprintId'));

        $this->commitment = new SprintCommitment(12, $this->sprint, PersonId::fromString('person'));
    }

    public function test_should_be_sprint_member()
    {
        $this->assertInstanceOfSprintMember($this->commitment);
    }

    public function test_should_return_the_available_man_days()
    {
        $this->assertSame(12, $this->commitment->getAvailableManDays());
    }

    public function test_should_support_available_man_days_as_string()
    {
        $commitment = new SprintCommitment('12', $this->sprint, PersonId::fromString('person'));
        $this->assertSame('12', $commitment->getAvailableManDays(), 'Man days must support string int.');
    }

    public function test_should_return_id()
    {
        $this->assertNull($this->commitment->getId());
    }

    public function test_should_return_the_sprint()
    {
        $this->assertSame($this->sprint, $this->commitment->getSprint());
    }

    /**
     * @ticket #57
     * @dataProvider provideInvalidManDays
     *
     * @param $manDays
     *
     * @expectedException        \Star\Component\Sprint\Exception\InvalidArgumentException
     * @expectedExceptionMessage The man days must be a numeric greater than zero.
     */
    public function test_should_throw_exception_when_invalid_man_days($manDays)
    {
        new SprintCommitment($manDays, $this->sprint, PersonId::fromString('person'));
    }

    public function provideInvalidManDays()
    {
        return array(
            'Man days cannot be zero' => array(0),
            'Man days cannot be negative' => array(-1),
            'Man days cannot be array' => array(array()),
            'Man days cannot be bool false' => array(false),
            'Man days cannot be bool true' => array(true),
            'Man days cannot be string' => array(''),
            'Man days cannot be float' => array(213.321),
        );
    }
}
