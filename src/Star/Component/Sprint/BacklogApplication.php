<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Command\Person\AddPersonCommand;
use Star\Component\Sprint\Command\Person\ListPersonCommand;
use Star\Component\Sprint\Command\Sprint\AddCommand as SprintAddCommand;
use Star\Component\Sprint\Command\Sprint\CloseSprintCommand;
use Star\Component\Sprint\Command\Sprint\JoinSprintCommand;
use Star\Component\Sprint\Command\Sprint\StartSprintCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Team\JoinCommand as JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand as TeamList;
use Star\Component\Sprint\Command\Sprint\ListCommand as SprintList;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

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
     * @var HelperSet
     */
    private $helperSet;

    /**
     * @param string $rootPath
     * @param string $env
     * @param array  $configuration
     */
    public function __construct($rootPath, $env = 'dev', array $configuration = array())
    {
        parent::__construct('backlog', '0.1');
        $this->helperSet = new HelperSet();

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
        $defaultHelperSet = $this->getDefaultHelperSet();
        foreach ($defaultHelperSet as $name => $helper) {
            // todo Add tests
            $this->addHelper($name, $helper);
        }
        $this->setHelperSet($this->helperSet);
        $plugin->build($this);

        $repositoryManager = $plugin->getRepositoryManager();
        $teamFactory = $plugin->getTeamFactory();

        $this->add(new SprintAddCommand($repositoryManager->getTeamRepository(), $repositoryManager->getSprintRepository()));
        $this->add(new SprintList($repositoryManager->getSprintRepository()));
        $this->add(new JoinSprintCommand($repositoryManager->getSprintRepository(), $repositoryManager->getTeamMemberRepository(), $repositoryManager->getSprintMemberRepository()));
        $this->add(new StartSprintCommand($repositoryManager->getSprintRepository()));
        $this->add(new CloseSprintCommand($repositoryManager->getSprintRepository()));
        $this->add(new TeamAddCommand($repositoryManager->getTeamRepository(), $teamFactory));
        $this->add(new TeamList($repositoryManager->getTeamRepository()));
        $this->add(new JoinTeamCommand($repositoryManager->getTeamRepository(), $repositoryManager->getPersonRepository(), $repositoryManager->getTeamMemberRepository()));
        $this->add(new AddPersonCommand($repositoryManager->getPersonRepository(), $teamFactory));
        $this->add(new ListPersonCommand($repositoryManager->getPersonRepository()));
    }

    /**
     * @param string $name
     * @param Helper $helper
     */
    public function addHelper($name, Helper $helper)
    {
        $this->helperSet->set($helper, $name);
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

    /**
     * @param string $personName
     *
     * @return int
     */
    public function createPerson($personName)
    {
        return $this->runCommand('backlog:person:add', array('name' => $personName));
    }

    /**
     * @param string $teamName
     *
     * @return int
     */
    public function createTeam($teamName)
    {
        return $this->runCommand('backlog:team:add', array('name' => $teamName));
    }

    /**
     * @param string $sprintName
     * @param string $teamName
     *
     * @return int
     */
    public function createSprint($sprintName, $teamName)
    {
        return $this->runCommand('backlog:sprint:add', array(
                'name' => $sprintName,
                'team' => $teamName,
            )
        );
    }

    /**
     * @param string $personName
     * @param string $teamName
     *
     * @return int
     */
    public function joinTeam($personName, $teamName)
    {
        return $this->runCommand('backlog:team:join', array(
                'person' => $personName,
                'team' => $teamName,
            )
        );
    }

    /**
     * @param string $sprintName
     * @param string $personName
     * @param int    $manDays
     *
     * @return int
     */
    public function joinSprint($sprintName, $personName, $manDays)
    {
        return $this->runCommand(
            'backlog:sprint:join',
            array(
                'sprint' => $sprintName,
                'person' => $personName,
                'man-days' => $manDays,
            )
        );
    }

    /**
     * @param string $sprintName
     * @param int    $estimatedVelocity
     *
     * @return int
     */
    public function startSprint($sprintName, $estimatedVelocity)
    {
        return $this->runCommand(
            'backlog:sprint:start',
            array(
                'name' => $sprintName,
                'estimated-velocity' => $estimatedVelocity,
            )
        );
    }

    /**
     * @param string $sprintName
     * @param int $actualVelocity
     *
     * @return int
     */
    public function stopSprint($sprintName, $actualVelocity)
    {
        return $this->runCommand(
            'backlog:sprint:close',
            array(
                'name' => $sprintName,
                'actual-velocity' => $actualVelocity,
            )
        );
    }

    /**
     * @param string $commandName
     * @param array $args
     *
     * @return int
     */
    private function runCommand($commandName, array $args)
    {
        $command = $this->find($commandName);
        return $command->run(
            new ArrayInput(array_merge(array(''), $args)),
            new NullOutput()
//            new ConsoleOutput()
        );
    }
}
