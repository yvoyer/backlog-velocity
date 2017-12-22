<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\Team;
use Star\BacklogVelocity\Agile\Domain\Model\TeamFactory;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullTeamFactory implements TeamFactory
{
    /**
     * Create a team object.
     *
     * @param string $name The name of the team.
     *
     * @return Team
     */
    public function createTeam($name)
    {
        throw new \RuntimeException('Method createTeam() not implemented yet.');
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
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
