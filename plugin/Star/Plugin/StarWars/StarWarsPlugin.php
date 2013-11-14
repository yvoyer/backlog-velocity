<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\StarWars;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * Class StarWarsPlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\StarWars
 */
class StarWarsPlugin implements BacklogPlugin
{
    /**
     * Returns the entity creator.
     *
     * @return EntityCreator
     */
    public function getEntityCreator()
    {
        throw new \RuntimeException('Method getEntityCreator() not implemented yet.');
    }

    /**
     * Returns the entity finder.
     *
     * @return EntityFinder
     */
    public function getEntityFinder()
    {
        throw new \RuntimeException('Method getEntityFinder() not implemented yet.');
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        throw new \RuntimeException('Method getRepositoryManager() not implemented yet.');
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        throw new \RuntimeException('Method getObjectManager() not implemented yet.');
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
        throw new \RuntimeException('Method build() not implemented yet.');
    }
}
 