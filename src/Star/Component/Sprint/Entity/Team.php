<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class Team
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface Team extends Entity
{
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
    public function addMember(Person $member);

    /**
     * Returns the members of the team.
     *
     * @return TeamMember[]
     */
    public function getMembers();

    /**
     * Returns the team available man days.
     *
     * @return integer
     */
    public function getAvailableManDays();

    /**
     * Returns the list of closed sprints.
     *
     * @return Sprint[]
     */
    public function getClosedSprints();

    /**
     * @param string $name
     * @param VelocityCalculator $calculator
     *
     * @return Sprint
     */
    public function startSprint($name, VelocityCalculator $calculator);

    /**
     * @return integer
     */
    public function getActualVelocity();
}
