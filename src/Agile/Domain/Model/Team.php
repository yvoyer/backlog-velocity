<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityAlreadyExistsException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface Team
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
     * @throws EntityAlreadyExistsException
     *
     * @return TeamMember
     */
    public function addTeamMember(Member $member) :TeamMember;

    /**
     * @param MemberId $personId
     *
     * @return TeamMember
     */
    public function joinMember(MemberId $personId) :TeamMember;

    /**
     * Returns the members of the team.
     *
     * @return MemberId[]
     */
    public function getTeamMembers() :array;
}
