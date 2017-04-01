<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\SprintCommitment;
use Star\Component\Sprint\Stub\Sprint\StubSprint;
use Star\Component\Sprint\UnitTestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintCommitmentTest extends UnitTestCase
{
    /**
     * @var SprintCommitment
     */
    private $commitment;

    public function setUp()
    {
        $this->commitment = new SprintCommitment(
            ManDays::fromInt(12),
            StubSprint::withId(SprintId::fromString('sprint-id')),
            PersonId::fromString('person')
        );
    }

    public function test_should_return_the_available_man_days()
    {
        $this->assertSame(12, $this->commitment->getAvailableManDays()->toInt());
    }

    public function test_should_return_id()
    {
        $this->assertAttributeSame('sprint-id_person', 'id', $this->commitment);
    }
}
