<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Visitor\ProjectVisitor;

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

    public function __construct(Team $team, MemberId $memberId)
    {
        $this->team = $team;
        $this->member = $memberId->toString();
    }

    public function acceptProjectVisitor(ProjectVisitor $visitor): void
    {
        $visitor->visitTeamMember($this);
    }

    public function matchPerson(MemberId $id): bool
    {
        return $id->matchIdentity($this->memberId());
    }

    public function memberId(): MemberId
    {
        return MemberId::fromString($this->member);
    }
}
