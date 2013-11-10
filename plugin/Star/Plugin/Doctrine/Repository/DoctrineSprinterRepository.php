<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Sprinter;

/**
 * Class DoctrineSprinterRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineSprinterRepository extends DoctrineRepository implements SprinterRepository
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
