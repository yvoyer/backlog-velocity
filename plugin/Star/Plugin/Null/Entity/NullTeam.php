<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamMember;

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
     */
    public function addTeamMember(Person $person)
    {
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
}
