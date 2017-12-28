<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Null;

use Star\BacklogVelocity\Agile\BacklogPlugin;
use Star\BacklogVelocity\Cli\BacklogApplication;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullPlugin implements BacklogPlugin
{
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
