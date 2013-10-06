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
     * @var Team
     */
    private $team;

    /**
     * @param Sprinter $member
     * @param Team     $team
     */
    public function __construct(Sprinter $member, Team $team)
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
     * @return Sprinter
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Returns the team.
     *
     * @return Team
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
