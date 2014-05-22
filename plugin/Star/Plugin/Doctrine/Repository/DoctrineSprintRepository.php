<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;

/**
 * Class SprintRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine\Repository
 */
class DoctrineSprintRepository extends DoctrineRepository implements SprintRepository
{
    /**
     * @param string $name
     *
     * @return Sprint
     */
    public function findOneByName($name)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
