<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Team;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamRepository
{
    /**
     * @return Team[]
     */
    public function allTeams();

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team|null
     */
    public function findOneByName($name);

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team);
}
