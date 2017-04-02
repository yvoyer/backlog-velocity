<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\TeamFactory;
use Star\Component\Sprint\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
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
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
    }
}
