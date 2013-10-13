<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Repository\Repository;

/**
 * Class DoctrineSprinterRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Doctrine
 */
class DoctrineSprinterRepository extends DoctrineRepository implements SprinterRepository
{
    /**
     * Return the Repository
     *
     * @return SprinterRepository
     */
    protected function getRepository()
    {
        return $this->getAdapter()->getSprinterRepository();
    }

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
