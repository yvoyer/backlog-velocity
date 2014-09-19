<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class PersonRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
interface PersonRepository extends Repository
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Person|null
     */
    public function findOneByName($name);
}
