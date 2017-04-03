<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\TeamId;
use Star\Component\Sprint\Model\PersonModel;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\TeamModel;
use Star\Component\Sprint\Model\TeamName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
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
        return new TeamModel(TeamId::fromString($name), new TeamName($name));
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
        return new PersonModel(PersonId::fromString($name), new PersonName($name));
    }
}