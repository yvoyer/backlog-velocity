<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Class TeamMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
class TeamMember implements MemberInterface, EntityInterface
{
    const LONG_NAME = __CLASS__;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var MemberInterface
     */
    private $member;

    /**
     * @var TeamInterface
     */
    private $team;

    /**
     * @param SprinterInterface $member
     * @param TeamInterface     $team
     */
    public function __construct(SprinterInterface $member, TeamInterface $team)
    {
        $this->member = $member;
        $this->team   = $team;
    }

    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the member.
     *
     * @return SprinterInterface
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Returns the team.
     *
     * @return TeamInterface
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     * @deprecated
     */
    public function toArray()
    {
        return array();// TODO: Implement toArray() method.
    }
}
