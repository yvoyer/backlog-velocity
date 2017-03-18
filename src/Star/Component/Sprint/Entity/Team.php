<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Model\Identity\TeamId;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
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
     * @return string
     */
    public function getName();

    /**
     * Add a $sprinter to the team.
     *
     * @param Person $member
     *
     * @return TeamMember
     */
    public function addTeamMember(Person $member);

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getTeamMembers();

    /**
     * Returns the list of closed sprints.
     *
     * @return Sprint[]
     */
    public function getClosedSprints();

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name);
}
