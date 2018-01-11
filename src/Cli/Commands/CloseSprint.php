<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Calculator\FocusCalculator;
use Star\BacklogVelocity\Agile\Application\Command\Sprint;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class CloseSprint extends Command
{
    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @param SprintRepository $sprintRepository
     */
    public function __construct(SprintRepository $sprintRepository)
    {
        parent::__construct('backlog:sprint:close');
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Stop a sprint.');
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the sprint to search.');
        $this->addArgument('actual-velocity', InputArgument::REQUIRED, 'Actual velocity for the sprint.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project name of the sprint to close.');
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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $actualVelocity = $input->getArgument('actual-velocity');
        $view = new ConsoleView($output);
        $project = $input->getArgument('project');

        try {
            $handler = new Sprint\CloseSprintHandler($this->sprintRepository, new FocusCalculator());
            $handler(Sprint\CloseSprint::fromString(
                $this->sprintRepository->sprintWithName(ProjectId::fromString($project), new SprintName($name))->getId()->toString(),
                (int) $actualVelocity)
            );

            $view->renderSuccess("Sprint '{$name}' of project '{$project}' is now closed.");
        } catch (EntityNotFoundException $ex) {
            $view->renderFailure("Sprint '{$name}' cannot be found in project '{$project}'.");
            return 1;
        } catch (\Throwable $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        return 0;
    }
}
