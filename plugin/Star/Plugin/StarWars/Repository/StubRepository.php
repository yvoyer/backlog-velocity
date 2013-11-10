<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars\Repository;

use Star\Component\Sprint\Entity\Team;
use Star\Plugin\StarWars\Team\CrimeSyndicate;
use Star\Plugin\StarWars\Team\GalacticEmpire;
use Star\Plugin\StarWars\Team\RebelAlliance;
use Star\Plugin\StarWars\Team\Siths;

/**
 * Class StubRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars\Repository
 */
class StubRepository
{
    /**
     * Returns all the teams
     *
     * @return Team[]
     */
    public function findAll()
    {
        return array(
            new GalacticEmpire(),
            new RebelAlliance(),
            new CrimeSyndicate(),
            new Siths(),
        );
    }

    /**
     * Returns the Team matching the $id, if not found, returns null.
     *
     * @param integer $id
     *
     * @return null|Team
     */
    public function findTeam($id)
    {
        $result = null;
        foreach ($this->findAll() as $team) {
            if ($team->getId() === $id) {
                $result = $team;
            }
        }

        return $result;
    }
}
