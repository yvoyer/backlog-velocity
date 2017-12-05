<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Model;

use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class TeamMemberModel implements TeamMember
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var string
     */
    private $member;

    /**
     * @param Team $team
     * @param MemberId $memberId
     */
    public function __construct(Team $team, MemberId $memberId)
    {
        $this->team = $team;
        $this->member = $memberId->toString();
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        $visitor->visitTeamMember(MemberId::fromString($this->member));
    }

    /**
     * @param MemberId $id
     *
     * @return bool
     */
    public function matchPerson(MemberId $id) :bool
    {
        return $id->matchIdentity(MemberId::fromString($this->member));
    }

    /**
     * @return TeamMemberDTO
     */
    public function teamMemberDto()
    {
        throw new \RuntimeException(__METHOD__);
        return new TeamMemberDTO($this->member->getId(), $this->member->getName());
    }
}
