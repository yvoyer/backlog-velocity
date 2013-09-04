<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Repository\WrappedRepository;

/**
 * Class SprinterRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Repository
 */
class SprinterRepository extends WrappedRepository
{
    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Sprinter|null
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }
}
