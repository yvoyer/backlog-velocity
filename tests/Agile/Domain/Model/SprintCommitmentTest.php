<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\BacklogVelocity\Agile\Domain\Stub\StubSprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintCommitmentTest extends TestCase
{
    /**
     * @var SprintCommitment
     */
    private $commitment;

    public function setUp()
    {
        $this->commitment = new SprintCommitment(
            ManDays::fromInt(12),
            StubSprint::withId(SprintId::uuid()),
            MemberId::fromString('person')
        );
    }

    public function test_should_return_the_available_man_days()
    {
        $this->assertSame(12, $this->commitment->getAvailableManDays()->toInt());
    }

    public function test_should_return_person_id()
    {
        $this->assertEquals(MemberId::fromString('person'), $this->commitment->member());
    }
}
