<?php

namespace Star\Plugin\InMemory;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Domain\BacklogPlugin;
use Star\Component\Sprint\Domain\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Domain\Repository\RepositoryManager;

final class InMemoryPlugin implements BacklogPlugin
{
    /**
     * Returns the entity creator.
     *
     * @return TeamFactory
     */
    public function getTeamFactory()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        return new CollectionManager();
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
