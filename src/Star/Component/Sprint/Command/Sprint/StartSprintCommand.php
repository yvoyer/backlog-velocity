<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class StartSprintCommand extends Command
{
    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @var VelocityCalculator
     */
    private $calculator;

    /**
     * @param SprintRepository $sprintRepository
     * @param VelocityCalculator $calculator
     */
    public function __construct(SprintRepository $sprintRepository, VelocityCalculator $calculator)
    {
        parent::__construct('backlog:sprint:start');
        $this->sprintRepository = $sprintRepository;
        $this->calculator = $calculator;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Start a sprint.');
        // todo rename to sprint id or sprint name with project id
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the sprint to search.');
        $this->addArgument('estimated-velocity', InputArgument::OPTIONAL, 'Estimated velocity for the sprint.');
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
        $estimatedVelocity = $input->getArgument('estimated-velocity');

        // todo Show possible estimates using a calculator (Composite)
        $sprint = $this->sprintRepository->findOneById(SprintId::fromString($name));
        $view = new ConsoleView($output);

        if (null === $sprint) {
            $view->renderFailure("Sprint '{$name}' cannot be found.");
            return 1;
        }

        $sprintManDays = $sprint->getManDays();
        if (! $sprintManDays->greaterThan(0)) {
            $view->renderFailure("Sprint member's commitments total should be greater than 0.");
            return 2;
        }

        if (null === $estimatedVelocity) {
            $suggested = $this->calculator->calculateEstimatedVelocity(
                $sprintManDays->toInt(),
                $this->sprintRepository
            );
            $view->renderNotice("I suggest: {$suggested} man days.");

            $estimatedVelocity = $this->getDialog()->askAndValidate(
                $output,
                '<question>What is the estimated velocity?</question>',
                array($this, 'assertValidAnswer')
            );
        }

        $this->assertValidAnswer($estimatedVelocity);

        $sprint->start($estimatedVelocity, new \DateTime());
        $this->sprintRepository->saveSprint($sprint);

        $view->renderSuccess("Sprint '{$name}' is now started.");
        return 0;
    }

    /**
     * @param int $estimatedVelocity
     *
     * @return int
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     */
    public function assertValidAnswer($estimatedVelocity)
    {
        if (empty($estimatedVelocity) || false === is_numeric($estimatedVelocity)) {
            throw new InvalidArgumentException('Estimated velocity must be numeric.');
        }

        return $estimatedVelocity;
    }

    /**
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @return DialogHelper
     */
    private function getDialog()
    {
        try {
            $dialog = $this->getHelper('dialog');
            return $dialog;
        } catch (\InvalidArgumentException $ex) {
            throw new InvalidArgumentException('The dialog helper is not configured.', null, $ex);
        }
    }
}
