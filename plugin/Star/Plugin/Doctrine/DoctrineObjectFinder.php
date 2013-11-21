<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\Sprinter;

/**
 * Class DoctrineObjectFinder
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine
 */
class DoctrineObjectFinder implements EntityFinder
{
    /**
     * @var DoctrineObjectManagerAdapter
     */
    private $adapter;

    /**
     * @param DoctrineObjectManagerAdapter $adapter
     */
    public function __construct(DoctrineObjectManagerAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Find a sprint with $name.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function findSprint($name)
    {
        return $this->adapter->getSprintRepository()->findOneBy(array('name' => $name));
    }

    /**
     * Find a sprinter with $name.
     *
     * @param string $name
     *
     * @return Sprinter
     */
    public function findSprinter($name)
    {
        return $this->adapter->getSprinterRepository()->findOneBy(array('name' => $name));
    }

    /**
     * Find a team with $name.
     *
     * @param string $name
     *
     * @return Team
     */
    public function findTeam($name)
    {
        return $this->adapter->getTeamRepository()->findOneBy(array('name' => $name));
    }
}