<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;
use Star\Plugin\Null\NullTeamFactory;
use Star\Plugin\Null\NullEntityFinder;
use Star\Plugin\Null\NullObjectManager;
use Star\Plugin\Null\NullRepositoryManager;

/**
 * Class NullPlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Null
 */
class NullPlugin implements BacklogPlugin
{
    /**
     * Returns the entity creator.
     *
     * @return TeamFactory
     */
    public function getTeamFactory()
    {
        return new NullTeamFactory();
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        return new NullRepositoryManager();
    }

    /**
     * Returns the object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return new NullObjectManager();
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
    }
}
 