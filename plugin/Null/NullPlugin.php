<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Domain\BacklogPlugin;
use Star\Component\Sprint\Domain\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Domain\Repository\RepositoryManager;

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
