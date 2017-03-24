<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Model;

use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Exception\DeprecatedFeatureException;
use Star\Component\Sprint\Model\Identity\PersonId;

/**
 * Class SprintMemberModel
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Model
 */
class SprintCommitment
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
        $this->assertManDaysIsValid($availableManDays);

        $this->availableManDays = $availableManDays->toInt();
        $this->sprint = $sprint;
        $this->member = $member;
    }

    /**
     * Returns the unique id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Returns the sprint
     *
     * @return Sprint|Sprint
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Returns the team member
     *
     * @return TeamMember
     * @deprecated todo remove
     */
    public function getTeamMember()
    {
        throw DeprecatedFeatureException::methodDeprecated(__METHOD__);
    }

    /**
     * Returns the name.
     *
     * @return string
     * @deprecated todo remove
     */
    public function getName()
    {
        throw DeprecatedFeatureException::methodDeprecated(__METHOD__);
        return $this->member->toString();
    }

    /**
     * @param int $availableManDays
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    private function assertManDaysIsValid($availableManDays)
    {
//        $exception = new InvalidArgumentException('The man days must be a numeric greater than zero.');
//
//        if (is_bool($availableManDays)) {
//            throw $exception;
//        }
//
//        if ($availableManDays != intval($availableManDays)) {
//            throw $exception;
//        }
//
//        if (false === ($availableManDays > 0)) {
//            throw $exception;
//        }
    }
}
