<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository\Doctrine;

use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Mapping\Entity;

/**
 * Class DoctrineTeamRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository\Doctrine
 */
class DoctrineTeamRepository extends DoctrineRepository implements TeamRepository
{
    /**
     * Return the Repository
     *
     * @return TeamRepository
     */
    protected function getRepository()
    {
        return $this->getAdapter()->getTeamRepository();
    }

    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Team|null
     */
    public function findOneByName($name)
    {
        return $this->getRepository()->findOneBy(array('name' => $name));
    }
}
