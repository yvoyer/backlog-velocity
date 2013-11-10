<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Plugin;

use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Interface BacklogPlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Plugin
 */
interface BacklogPlugin
{
    /**
     * Returns the entity creator.
     *
     * @return EntityCreator
     */
    public function getEntityCreator();

    /**
     * Returns the entity finder.
     *
     * @return EntityFinder
     */
    public function getEntityFinder();

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager();

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager();
}
 