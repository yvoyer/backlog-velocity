<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Query;

use Doctrine\Common\Persistence\ObjectManager;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;

/**
 * Class DoctrineObjectFinder
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Query
 */
class DoctrineObjectFinder implements EntityFinder
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    private function findByName($entityName, $name)
    {
        return $this->objectManager->getRepository($entityName)->findOneBy(array('name' => $name));
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
        return $this->findByName(SprintData::LONG_NAME, $name);
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
        return $this->findByName(SprinterData::LONG_NAME, $name);
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
        return $this->findByName(TeamData::LONG_NAME, $name);
    }
}
