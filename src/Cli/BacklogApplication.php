<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli;

use Star\BacklogVelocity\Agile\Application\Calculator\ResourceCalculator;
use Star\BacklogVelocity\Agile\BacklogPlugin;
use Star\BacklogVelocity\Cli\Commands;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class BacklogApplication extends Application
{
    const VERSION = '2.0.0-rc1';

    public function __construct()
    {
        parent::__construct('backlog', self::VERSION);
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
        $persons = $repositoryManager->getPersonRepository();
        $teams = $repositoryManager->getTeamRepository();
        $projects = $repositoryManager->getProjectRepository();
        $sprints = $repositoryManager->getSprintRepository();

        // todo put cli in plugin?
        $this->add(new Commands\CreateProject($projects));
        $this->add(new Commands\CreateSprint($projects, $sprints, $teams));
        $this->add(new Commands\ListSprints($sprints));
        $this->add(new Commands\JoinSprint($sprints, $persons));
        $this->add(new Commands\StartSprint($sprints, new ResourceCalculator($sprints)));
        $this->add(new Commands\CloseSprint($sprints));
        $this->add(new Commands\CreateTeam($teams));
        $this->add(new Commands\ListTeams($teams));
        $this->add(new Commands\JoinTeam($teams, $persons));
        $this->add(new Commands\CreatePerson($persons));
        $this->add(new Commands\ListPersons($persons));
        $this->add(new Commands\RunCommand($this));
    }

    /**
     * @param string $projectName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createProject(string $projectName, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:project:create', array('name' => $projectName), $output);
    }

    /**
     * @param string $personName
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createPerson(string $personName, OutputInterface $output = null)
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
    public function createTeam(string $teamName, OutputInterface $output = null)
    {
        return $this->runCommand(
            'backlog:team:add',
            [
                'name' => $teamName,
            ],
            $output
        );
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
     * @param string $teamId
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function createSprint(string $sprintName, string $projectId, string $teamId, OutputInterface $output = null)
    {
        return $this->runCommand('backlog:sprint:add', array(
                'name' => $sprintName,
                'project' => $projectId,
                'team' => $teamId,
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
    public function joinTeam(string $personName, string $teamName, OutputInterface $output = null)
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
    public function joinSprint(string $projectId, string $sprintName, string $personName, int $manDays, OutputInterface $output = null)
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
     * @param int $plannedVelocity
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    public function startSprint(string $project, string $sprintName, int $plannedVelocity, OutputInterface $output = null)
    {
        $args = [
            'name' => $sprintName,
            'project' => $project,
        ];
        if (intval($plannedVelocity) > 0) {
            $args['planned-velocity'] = (int) $plannedVelocity;
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
    public function stopSprint(string $project, string $sprintName, int $actualVelocity, OutputInterface $output = null)
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
     * @param OutputInterface|null $output
     *
     * @return bool
     */
    public function install(OutputInterface $output = null) {
        return $this->runCommand('update', [], $output);
    }

    /**
     * @param string $commandName
     * @param array $args
     * @param OutputInterface $output
     *
     * @return bool Return true on success, false on error.
     */
    private function runCommand(string $commandName, array $args, OutputInterface $output = null)
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
