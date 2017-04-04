<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\TeamName;
use Star\Component\Sprint\Port\TeamMemberDTO;

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
     * @param Person $member
     */
    public function addTeamMember(Person $member);

    /**
     * Returns the members of the team.
     *
     * @return TeamMemberDTO[]
     */
    public function getTeamMembers();
}
