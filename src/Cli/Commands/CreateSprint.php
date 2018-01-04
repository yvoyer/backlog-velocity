<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Command\Sprint;
use Star\BacklogVelocity\Agile\Application\Naming\AlwaysReturnSprintName;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Agile\Domain\Model\SprintId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreateSprint extends Command
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @var TeamRepository
     */
    private $teams;

    /**
     * @param ProjectRepository $projectRepository
     * @param SprintRepository $sprintRepository
     * @param TeamRepository $teams
     */
    public function __construct(
        ProjectRepository $projectRepository,
        SprintRepository $sprintRepository,
        TeamRepository $teams
    ) {
        parent::__construct('backlog:sprint:add');

        $this->projectRepository = $projectRepository;
        $this->sprintRepository = $sprintRepository;
        $this->teams = $teams;
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the sprint.');
        $this->addArgument('team', InputArgument::REQUIRED, 'The team id in which to create the sprint.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project id in which to create the sprint.');
        $this->setDescription('Create a new sprint for the team.');
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
        $sprintName = $input->getArgument('name'); // todo remove, use auto increment
        $projectName = $input->getArgument('project');
        $teamId = $input->getArgument('team');

        $view = new ConsoleView($output);
        try {
            $handler = new Sprint\CreateSprintHandler(
                $this->projectRepository,
                $this->sprintRepository,
                $this->teams,
                new AlwaysReturnSprintName(new SprintName($sprintName))
            );
            $handler(new Sprint\CreateSprint(
                SprintId::uuid(),
                ProjectId::fromString($projectName),
                TeamId::fromString($teamId)
            ));

            $view->renderSuccess('The sprint was successfully saved.');
        } catch (BacklogException $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        return 0;
    }
}
