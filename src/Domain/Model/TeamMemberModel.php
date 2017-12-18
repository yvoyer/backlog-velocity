<?php declare(strict_types=1);
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
        $visitor->visitTeamMember($this);
    }

    /**
     * @param MemberId $id
     *
     * @return bool
     */
    public function matchPerson(MemberId $id) :bool
    {
        return $id->matchIdentity($this->memberId());
    }

    /**
     * @return MemberId
     */
    public function memberId(): MemberId
    {
        return MemberId::fromString($this->member);
    }
}
