<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\TeamModel;

/**
 * Class BacklogModelTeamFactory
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 *
 * @todo Rename to BacklogModelFactory
 */
class BacklogModelTeamFactory implements TeamFactory
{
    /**
     * Create a team object.
     *
     * @param string $name
     *
     * @return Team
     */
    public function createTeam($name)
    {
        return new TeamModel($name);
    }

    /**
     * Create a person object.
     *
     * @param string $name
     *
     * @return Person
     */
    public function createPerson($name)
    {
        return new PersonModel($name);
    }
}
