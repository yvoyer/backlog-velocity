<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli;

use Star\Component\Sprint\Domain\BacklogPlugin;
use Star\Component\Sprint\Domain\Calculator\ResourceCalculator;
use Star\BacklogVelocity\Application\Cli\Commands;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class BacklogApplication extends Application
{
    const VERSION = '2.0.0-beta';

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
        parent::__construct('backlog', self::VERSION);
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

        // todo put cli in plugin?
        $this->add(new Commands\CreateProject($repositoryManager->getProjectRepository()));
        $this->add(new Commands\CreateSprint($repositoryManager->getProjectRepository(), $repositoryManager->getSprintRepository()));
        $this->add(new Commands\ListSprints($repositoryManager->getSprintRepository()));
        $this->add(new Commands\JoinSprint($repositoryManager->getSprintRepository(), $repositoryManager->getPersonRepository()));
        $this->add(new Commands\StartSprint($repositoryManager->getSprintRepository(), new ResourceCalculator()));
        $this->add(new Commands\CloseSprint($repositoryManager->getSprintRepository()));
        $this->add(new Commands\CreateTeam($repositoryManager->getTeamRepository(), $teamFactory));
        $this->add(new Commands\ListTeams($repositoryManager->getTeamRepository()));
        $this->add(new Commands\JoinTeam($repositoryManager->getTeamRepository(), $repositoryManager->getPersonRepository()));
        $this->add(new Commands\CreatePerson($repositoryManager->getPersonRepository(), $teamFactory));
        $this->add(new Commands\ListPersons($repositoryManager->getPersonRepository()));
        $this->add(new Commands\RunCommand($this));
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
     * @param string $projectName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createProject($projectName, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:project:create', array('name' => $projectName), $output);
    }

    /**
     * @param string $personName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createPerson($personName, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:person:add', array('name' => $personName), $output);
    }

    /**
     * @param OutputInterface $output
     *
     * @return bool
     */
    public function listPersons(OutputInterface $output = null)
    {
        return $this->runCommand('backlog:person:list', array(), $output);
    }

    /**
     * @param string $teamName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createTeam($teamName, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:team:add', array('name' => $teamName), $output);
    }

    /**
     * @param OutputInterface $output
     *
     * @return bool
     */
    public function listTeams(OutputInterface $output = null)
    {
        return $this->runCommand('backlog:team:list', array(), $output);
    }

    /**
     * @param string $sprintName
     * @param string $projectId
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createSprint($sprintName, $projectId, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:sprint:add', array(
                'name' => $sprintName,
                'project' => $projectId,
            ),
            $output
        );
    }

    /**
     * @param string $personName
     * @param string $teamName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function joinTeam($personName, $teamName, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:team:join', array(
                'person' => $personName,
                'team' => $teamName,
            ),
            $output
        );
    }

    /**
     * @param string $projectId
     * @param string $sprintName
     * @param string $personName
     * @param int    $manDays
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function joinSprint($projectId, $sprintName, $personName, $manDays, OutputInterface $output = null)
    {
        return $this->runCommand(
            'backlog:sprint:join',
            array(
                'project' => $projectId,
                'sprint' => $sprintName,
                'person' => $personName,
                'man-days' => $manDays,
            ),
            $output
        );
    }

    /**
     * @param string $project
     * @param string $sprintName
     * @param int $estimatedVelocity
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function startSprint($project, $sprintName, $estimatedVelocity, OutputInterface $output = null)
    {
        $args = [
            'name' => $sprintName,
            'project' => $project,
        ];
        if (intval($estimatedVelocity) > 0) {
            $args['estimated-velocity'] = (int) $estimatedVelocity;
        } else {
            $args['--accept-suggestion'] = true;
        }

        return $this->runCommand('backlog:sprint:start', $args, $output);
    }

    /**
     * @param string $project
     * @param string $sprintName
     * @param int $actualVelocity
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function stopSprint($project, $sprintName, $actualVelocity, OutputInterface $output = null)
    {
        return $this->runCommand(
            'backlog:sprint:close',
            array(
                'name' => $sprintName,
                'project' => $project,
                'actual-velocity' => $actualVelocity,
            ),
            $output
        );
    }

    /**
     * @param string $commandName
     * @param array $args
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    private function runCommand($commandName, array $args, OutputInterface $output = null)
    {
        if (null === $output) {
            $output = new NullOutput();
        }

        $command = $this->find($commandName);
        return ! (bool) $command->run(
            new ArrayInput(array_merge(array(''), $args)),
            $output
        );
    }
}
