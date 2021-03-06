<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogAssertion;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintCommitment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $availableManDays;

    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var string
     */
    private $member;

    /**
     * @param ManDays $availableManDays
     * @param Sprint $sprint
     * @param MemberId $memberId
     */
    public function __construct(ManDays $availableManDays, Sprint $sprint, MemberId $memberId)
    {
        $this->id = $sprint->getId()->toString() . '_' . $memberId->toString();
        $this->availableManDays = $availableManDays->toInt();
        BacklogAssertion::greaterThan($this->availableManDays, 0, 'Cannot commit with a number of days not greater than zero, "0" given.');
        $this->sprint = $sprint;
        $this->member = $memberId->toString();
    }

    /**
     * @return MemberId
     */
    public function member()
    {
        return MemberId::fromString($this->member);
    }

    /**
     * Returns the available man days.
     *
     * @return ManDays
     */
    public function getAvailableManDays()
    {
        return ManDays::fromInt($this->availableManDays);
    }
}
