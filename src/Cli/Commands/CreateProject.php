<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Command\Project;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateProject extends Command
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
    {
        parent::__construct('backlog:project:create');
        $this->repository = $repository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a project.');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the project.');
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
        $view = new ConsoleView($output);
        $projectName = $input->getArgument('name');

        try {
            $handler = new Project\CreateProjectHandler($this->repository);
            $handler(Project\CreateProject::fromString($projectName, $projectName));
        } catch (BacklogException $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        $view->renderSuccess("The project '{$projectName}' was successfully saved.");
        return 0;
    }
}
