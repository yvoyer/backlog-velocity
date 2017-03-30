<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CloseSprintCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
 */
class CloseSprintCommand extends Command
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

        $sprint = $this->sprintRepository->findOneById(SprintId::fromString($name));
        $view = new ConsoleView($output);

        if (null === $sprint) {
            $view->renderFailure("Sprint '{$name}' cannot be found.");
            return 1;
        }

        $sprint->close($actualVelocity, new \DateTimeImmutable());
        $this->sprintRepository->saveSprint($sprint);

        $view->renderSuccess("Sprint '{$name}' is now closed.");
        return 0;
    }
}
