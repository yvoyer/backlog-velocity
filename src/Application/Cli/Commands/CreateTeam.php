<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Domain\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Star\Component\Sprint\Domain\Model\Identity\TeamId;
use Star\Component\Sprint\Domain\Model\TeamName;
use Star\Component\Sprint\Domain\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreateTeam extends Command
{
    /**
     * The object repository.
     *
     * @var TeamRepository
     */
    private $repository;

    /**
     * @var ProjectRepository
     */
    private $projects;

    /**
     * @param TeamRepository $repository
     * @param ProjectRepository $projects
     */
    public function __construct(TeamRepository $repository, ProjectRepository $projects)
    {
        parent::__construct('backlog:team:add');
        $this->repository = $repository;
        $this->projects = $projects;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a team.');
        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the team to add'
        );
        $this->addOption(
            'project',
            'p',
            InputOption::VALUE_REQUIRED,
            'The project on which to link the team.'
        );
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|integer null or 0 if everything went fine, or an error code
     *
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $teamName = $input->getArgument('name');
        $view = new ConsoleView($output);

        $projectOption = $input->getOption('project');
        if (empty($projectOption)) {
            throw new \InvalidArgumentException('The option --project must be supplied.');
        }

        $project = $this->projects->getProjectWithIdentity(ProjectId::fromString($projectOption));

        if ($this->repository->teamWithNameExists($teamName)) {
            $view->renderFailure("The team '{$teamName}' already exists.");
            return 1;
        }

        $team = $project->createTeam(TeamId::fromString($teamName), new TeamName($teamName));
        $this->repository->saveTeam($team);
        $view->renderSuccess("The team '{$teamName}' was successfully saved.");
        return 0;
    }
}
