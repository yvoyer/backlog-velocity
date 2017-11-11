<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Entity\Sprint;
use Star\Component\Sprint\Domain\Exception\BacklogAssertion;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;

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
    private $person;

    /**
     * @param ManDays $availableManDays
     * @param Sprint $sprint
     * @param PersonId $personId
     */
    public function __construct(ManDays $availableManDays, Sprint $sprint, PersonId $personId)
    {
        $this->id = $sprint->getId()->toString() . '_' . $personId->toString();
        $this->availableManDays = $availableManDays->toInt();
        BacklogAssertion::greaterThan($this->availableManDays, 0, 'Cannot commit with a number of days not greater than zero, "0" given.');
        $this->sprint = $sprint;
        $this->person = $personId->toString();
    }

    /**
     * @return PersonId
     */
    public function member()
    {
        return PersonId::fromString($this->person);
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
