<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\Member;
use Star\Component\Sprint\Domain\Visitor\ProjectNode;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Component\Sprint\Domain\Port\TeamMemberDTO;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface Team extends ProjectNode
{
    /**
     * @return TeamId
     */
    public function getId();

    /**
     * Returns the team name.
     *
     * @return TeamName
     */
    public function getName();

    /**
     * Add a $sprinter to the team.
     *
     * @param Member $member
     *
     * @throws \Star\Component\Sprint\Domain\Exception\EntityAlreadyExistsException
     *
     * @return TeamMember
     */
    public function addTeamMember(Member $member) :TeamMember;

    /**
     * @param PersonId $personId
     *
     * @return TeamMember
     */
    public function joinMember(PersonId $personId) :TeamMember;

    /**
     * Returns the members of the team.
     *
     * @return TeamMemberDTO[]
     */
    public function getTeamMembers();
}
