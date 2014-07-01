<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartSprintCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
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
     */
    public function __construct(SprintRepository $sprintRepository, VelocityCalculator $calculator = null)
    {
        parent::__construct('backlog:sprint:start');
        $this->sprintRepository = $sprintRepository;

        $this->calculator = $calculator;
        if (null === $this->calculator) {
            $this->calculator = new ResourceCalculator();
        }
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Start a sprint.');
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
        $sprint = $this->sprintRepository->findOneByName($name);
        $view = new ConsoleView($output);

        if (null === $sprint) {
            $view->renderFailure("Sprint '{$name}' cannot be found.");
            return 1;
        }

        if (null === $estimatedVelocity) {
            $suggested = $this->calculator->calculateEstimatedVelocity(
                $sprint->getManDays(),
                new SprintCollection($sprint->getTeam()->getClosedSprints())
            );
            $view->renderNotice("I suggest: {$suggested} man days");

            $estimatedVelocity = $this->getDialog()->askAndValidate(
                $output,
                '<question>What is the estimated velocity?</question>',
                array($this, 'assertValidAnswer')
            );
        }

        $this->assertValidAnswer($estimatedVelocity);

        $sprint->start($estimatedVelocity);
        $this->sprintRepository->add($sprint);
        $this->sprintRepository->save();

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
