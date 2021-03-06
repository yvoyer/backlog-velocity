<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Command\Sprint\StartSprint as StartSprintCommand;
use Star\BacklogVelocity\Agile\Application\Command\Sprint\StartSprintHandler;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectId;
use Star\BacklogVelocity\Agile\Domain\Model\SprintName;
use Star\BacklogVelocity\Agile\Domain\Model\SprintRepository;
use Star\BacklogVelocity\Agile\Domain\Model\VelocityCalculator;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
        $this->addArgument('planned-velocity', InputArgument::OPTIONAL, 'Planned velocity for the sprint.');
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
        $plannedVelocity = $input->getArgument('planned-velocity');
        $useSuggested = $input->getOption('accept-suggestion');
        $view = new ConsoleView($output);

        try {
            // todo Show possible estimates using a calculator (Composite)
            // todo when no velocity given, accept the suggested one, unless manual is entered
            $sprint = $this->sprintRepository->sprintWithName(
                ProjectId::fromString($input->getArgument('project')),
                new SprintName($name)
            );

            if (null === $plannedVelocity) {
                $plannedVelocity = $this->calculator->calculateEstimatedVelocity($sprint->getId())->toInt();

                if (! $useSuggested) {
                    $view->renderNotice("I suggest: {$plannedVelocity} man days.");
                    $question = new Question('<question>What is the estimated velocity?</question>');
                    $question->setValidator(array($this, 'assertValidAnswer'));
                    $plannedVelocity = $this->getDialog()->ask(
                        $input,
                        $output,
                        $question
                    );
                }
            }

            // todo use more recent version of Question update Component
            $this->assertValidAnswer($plannedVelocity);

            $handler = new StartSprintHandler($this->sprintRepository);
            $handler(new StartSprintCommand($sprint->getId(), (int) $plannedVelocity));

            if ($useSuggested) {
                $view->renderNotice(
                    "I started the sprint '{$name}' with the suggested velocity of {$plannedVelocity} Story points."
                );
            } else {
                $view->renderSuccess("Sprint '{$name}' is now started.");
            }
        } catch (EntityNotFoundException $ex) {
            $view->renderFailure("Sprint '{$name}' cannot be found.");
            return 1;
        } catch (BacklogException $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * @param int $plannedVelocity
     *
     * @return int
     * @throws InvalidArgumentException
     */
    public function assertValidAnswer($plannedVelocity)
    {
        if (! (is_numeric($plannedVelocity) && (int) $plannedVelocity > 0)) {
            throw new InvalidArgumentException('Planned velocity must be numeric.');
        }

        return $plannedVelocity;
    }

    /**
     * @throws InvalidArgumentException
     * @return QuestionHelper
     */
    private function getDialog()
    {
        try {
            return $this->getHelper('dialog');
        } catch (\LogicException $ex) {
            throw new InvalidArgumentException('The dialog helper is not configured.');
        }
    }
}
