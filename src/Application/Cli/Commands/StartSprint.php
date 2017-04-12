<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Calculator\VelocityCalculator;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Exception\BacklogException;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\SprintName;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class StartSprint extends Command
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
        // todo name should be optional --name, else it will be "Sprint X"
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the sprint to search.');
        $this->addArgument('project', InputArgument::REQUIRED, 'The project where the sprint is.');
        $this->addArgument('estimated-velocity', InputArgument::OPTIONAL, 'Estimated velocity for the sprint.');
        $this->addOption(
            'accept-suggestion',
            'a',
            InputOption::VALUE_NONE,
            'Do not ask for an estimated velocity, accepts the calculated suggestion.'
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
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $estimatedVelocity = $input->getArgument('estimated-velocity');
        $view = new ConsoleView($output);

        try {
            // todo Show possible estimates using a calculator (Composite)
            $sprint = $this->sprintRepository->sprintWithName(
                ProjectId::fromString($input->getArgument('project')),
                new SprintName($name)
            );
            $useSuggested = $input->getOption('accept-suggestion');

//            if (null === $sprint) {
//                $view->renderFailure("Sprint '{$name}' cannot be found.");
//                return 1;
//            }

            $sprintManDays = $sprint->getManDays();
//            if (! $sprintManDays->greaterThan(0)) {
//                $view->renderFailure("Sprint member's commitments total should be greater than 0. Did you commit any member?");
//                return 2;
//            }

            // todo when no velocity given, accept the suggested one, unless manual is entered
            if (null === $estimatedVelocity) {
                $estimatedVelocity = $this->calculator->calculateEstimatedVelocity(
                    $sprint->projectId(),
                    $sprintManDays,
                    $this->sprintRepository
                );

                if (! $useSuggested) {
                    $view->renderNotice("I suggest: {$estimatedVelocity} man days.");
                    $estimatedVelocity = $this->getDialog()->askAndValidate(
                        $output,
                        '<question>What is the estimated velocity?</question>',
                        array($this, 'assertValidAnswer')
                    );
                }
            }

            // todo use more recent version of Question update Component
            $this->assertValidAnswer($estimatedVelocity);

            $sprint->start($estimatedVelocity, new \DateTime());
            $this->sprintRepository->saveSprint($sprint);

            if ($useSuggested) {
                $view->renderNotice(
                    "I started the sprint '{$name}' with the suggested velocity of {$estimatedVelocity} Story points."
                );
            } else {
                $view->renderSuccess("Sprint '{$name}' is now started.");
            }
        } catch (BacklogException $ex) {
       //     $view->renderFailure("Sprint '{$name}' cannot be found.");
            return 1;
        }

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
        if (! (is_numeric($estimatedVelocity) && (int) $estimatedVelocity > 0)) {
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
