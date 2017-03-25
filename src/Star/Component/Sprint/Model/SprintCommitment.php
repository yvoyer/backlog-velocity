<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Model\Identity\PersonId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class SprintCommitment
{
    const LONG_NAME = __CLASS__;

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
     * @var PersonId
     */
    private $member;

    /**
     * @param ManDays $availableManDays
     * @param Sprint $sprint
     * @param PersonId $member
     */
    public function __construct(ManDays $availableManDays, Sprint $sprint, PersonId $member)
    {
        $this->id = $sprint->getId()->toString() . '_' . $member->toString();
        $this->availableManDays = $availableManDays->toInt();
        $this->sprint = $sprint;
        $this->member = $member;
    }

    /**
     * @return PersonId
     */
    public function member()
    {
        return $this->member;
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
