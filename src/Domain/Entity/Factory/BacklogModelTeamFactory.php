<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity\Factory;

use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonModel;
use Star\Component\Sprint\Domain\Model\PersonName;

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
        throw new \RuntimeException(__METHOD__ . ' deprecated.');
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
