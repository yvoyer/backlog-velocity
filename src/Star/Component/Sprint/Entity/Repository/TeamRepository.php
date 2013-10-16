<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class TeamRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
interface TeamRepository extends Repository
{
    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team|null
     */
    public function findOneByName($name);
}
