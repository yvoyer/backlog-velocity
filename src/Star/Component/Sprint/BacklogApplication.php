<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Command\Sprint\AddCommand as SprintAddCommand;
use Star\Component\Sprint\Command\Sprint\UpdateCommand as SprintUpdateCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Team\JoinCommand as JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand as TeamList;
use Star\Component\Sprint\Command\Sprint\ListCommand as SprintList;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Symfony\Component\Console\Application;

/**
 * Class BacklogApplication
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class BacklogApplication extends Application
{
    /**
     * @todo Define as object
     *
     * @var array
     */
    private $configuration;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @param string $rootPath
     * @param string $env
     * @param array  $configuration
     */
    public function __construct($rootPath, $env = 'dev', array $configuration = array())
    {
        parent::__construct('backlog', '0.1');

        $this->rootPath      = $rootPath;
        $this->configuration = $configuration;
        $this->environment   = $env;
    }

    /**
     * Register a new plugin in the application.
     *
     * @param BacklogPlugin $plugin
     */
    public function registerPlugin(BacklogPlugin $plugin)
    {
        $plugin->build($this);

        $repositoryManager = $plugin->getRepositoryManager();
        $objectCreator     = $plugin->getEntityCreator();
        $objectFinder      = $plugin->getEntityFinder();

        $this->add(new SprintAddCommand($repositoryManager->getSprintRepository(), $objectCreator, $objectFinder));
        $this->add(new SprintList($repositoryManager->getSprintRepository()));
        $this->add(new SprintUpdateCommand($objectFinder, $repositoryManager->getSprintRepository()));
        $this->add(new TeamAddCommand($repositoryManager->getTeamRepository(), $objectCreator));
        $this->add(new TeamList($repositoryManager->getTeamRepository()));
        $this->add(new JoinTeamCommand($objectCreator, $objectFinder, $repositoryManager->getTeamMemberRepository()));
    }

    /**
     * Returns the configuration for the application
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Returns the environment
     * @todo Move to Configuration
     * @return string
     * @deprecated
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Returns the root path.
     * @todo Move to Configuration
     * @return string
     *
     * @deprecated
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }
}
