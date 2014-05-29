<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Plugin;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\TeamFactory;
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
     * @return TeamFactory
     */
    public function getTeamFactory();

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager();

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application);
}
 