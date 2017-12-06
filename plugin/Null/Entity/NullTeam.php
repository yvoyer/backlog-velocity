<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Member;
use Star\Component\Sprint\Domain\Visitor\ProjectVisitor;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Entity\TeamMember;
use Star\Component\Sprint\Domain\Model\TeamName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullTeam implements Team
{
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
     * @return mixed
     */
    public function getId()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
     * @param PersonId $personId
     *
     * @return TeamMember
     */
    public function joinMember(PersonId $personId): TeamMember
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
