<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Model\Identity\TeamId;

/**
 * Class NullTeam
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null\Entity
 */
class NullTeam implements Team
{
    /**
     * Returns the team name.
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Add a $sprinter to the team.
     *
     * @param Person $person
     *
     * @return TeamMember
     */
    public function addTeamMember(Person $person)
    {
        return new NullTeamMember();
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
     * @return TeamMember[]
     */
    public function getTeamMembers()
    {
        return array();
    }

    /**
     * Returns the list of closed sprints.
     *
     * @return Sprint[]
     */
    public function getClosedSprints()
    {
        return array();
    }

    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
