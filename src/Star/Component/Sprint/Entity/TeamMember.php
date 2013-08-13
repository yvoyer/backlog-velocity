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
class TeamMember implements MemberInterface, TeamInterface
{
    /**
     * @var MemberInterface
     */
    private $member;

    /**
     * @var TeamInterface
     */
    private $team;

    /**
     * @param MemberInterface $member
     * @param TeamInterface $team
     */
    public function __construct(MemberInterface $member, TeamInterface $team)
    {
        $this->member = $member;
        $this->team   = $team;
    }

    /**
     * Returns the member.
     *
     * @return MemberInterface
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
}
