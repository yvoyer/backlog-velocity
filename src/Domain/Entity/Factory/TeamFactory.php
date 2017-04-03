<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamFactory
{
    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
     */
    public function createTeam($name);

    /**
     * Create a person object.
     *
     * @param string $name
     *
     * @return Person
     */
    public function createPerson($name);
}