<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\Member;
use Star\BacklogVelocity\Agile\Domain\Model\MemberId;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamMember;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Visitor\ProjectVisitor;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class NullTeam implements Team
{
    /**
     * @var TeamId
     */
    private $id;

    public function __construct()
    {
        $this->id = TeamId::uuid();
    }

    /**
     * Returns the team name.
     *
     * @return TeamName
     */
    public function getName()
    {
        return new TeamName('');
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param Member $member
     *
     * @return TeamMember
     */
    public function addTeamMember(Member $member) :TeamMember
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the unique id.
     *
     * @return TeamId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the members of the team.
     *
     * @return MemberId[]
     */
    public function getTeamMembers() :array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param ProjectVisitor $visitor
     */
    public function acceptProjectVisitor(ProjectVisitor $visitor)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param MemberId $personId
     *
     * @return TeamMember
     */
    public function joinMember(MemberId $personId): TeamMember
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
