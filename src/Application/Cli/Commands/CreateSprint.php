<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Entity\Repository\ProjectRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Star\Component\Sprint\Exception\BacklogException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreateSprint extends Command
{
    /**
     * @var TeamRepository
     */
    private $projectRepository;

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @param ProjectRepository $projectRepository
     * @param SprintRepository $sprintRepository
     */
    public function __construct(ProjectRepository $projectRepository, SprintRepository $sprintRepository)
    {
        parent::__construct('backlog:sprint:add');

        $this->projectRepository = $projectRepository;
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the sprint.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project in which to create the sprint.');
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
        $sprintName = $input->getArgument('name');
        $projectName   = $input->getArgument('project');

        $view = new ConsoleView($output);
        try {
            $project = $this->projectRepository->getProjectWithIdentity(ProjectId::fromString($projectName));
            $sprint = $project->createSprint(SprintId::fromString($sprintName), new \DateTimeImmutable());
            $this->sprintRepository->saveSprint($sprint);
            $view->renderSuccess('The sprint was successfully saved.');
        } catch (BacklogException $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        return 0;
    }
}
