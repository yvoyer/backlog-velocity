<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Exception\EntityNotFoundException;

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
     * @return Team
     * @throws EntityNotFoundException
     */
    public function findOneByName($name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function teamWithNameExists($name);

    /**
     * @param Team $team
     */
    public function saveTeam(Team $team);
}
