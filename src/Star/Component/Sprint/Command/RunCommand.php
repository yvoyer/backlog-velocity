<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command;

use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Calculator\ResourceCalculator;
use Star\Component\Sprint\Collection\PersonCollection;
use Star\Component\Sprint\Collection\SprintCollection;
use Star\Component\Sprint\Command\Question\AskManDaysQuestion;
use Star\Component\Sprint\Command\Question\JoinSprintQuestion;
use Star\Component\Sprint\Command\Question\StartSprintQuestion;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * Class RunCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command
 */
class RunCommand extends Command
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var QuestionHelper
     */
    private $question;

    /**
     * @var DialogHelper
     */
    private $dialog;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    public function __construct(BacklogApplication $application, SprintRepository $sprintRepository, PersonRepository $personRepository)
    {
        $this->application = $application;
        $this->sprintRepository = $sprintRepository;
        $this->personRepository = $personRepository;

        parent::__construct('run');
    }

    protected function configure()
    {
        $this->setDescription('Interactively use the application, in one command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelper('dialog');
        $this->question = $this->getHelper('question');
        $this->input = $input;
        $this->output = $output;

        $option = 999;
        while ($option > 0) {
            $options = array(
                1 => 'Create user',
                2 => 'Create team',
                3 => 'Add team member',
                4 => 'Manage sprint',
                0 => 'Exit',
            );

            // Show options
            foreach ($options as $key => $optionToShow) {
                $output->writeln("<info>[{$key}] {$optionToShow}</info>");
            }

            // Choose action
            $option = $this->dialog->askAndValidate(
                $output,
                '<info>What would you like to do?</info> ',
                function($input) use ($options) {
                    if (false === array_key_exists($input, $options)) {
                        throw new InvalidArgumentException('You must choose a valid option.');
                    }

                    return $input;
                }
            );

            // Dispatch
            switch ($option) {
                case 1:
                    $this->createPersons($output);
                    break;

                case 2:
                    $this->createTeams($output);
                    break;

                case 3:
                    $this->addTeamMember($output);
                    break;

                case 4:
                    $this->manageSprints($output);
                    break;
            }
        }

        $output->writeln('Bye');
    }

    /**
     * @param OutputInterface $output
     */
    private function createPersons(OutputInterface $output)
    {
        $this->application->listPersons($output);
        $createUser = $this->dialog->askConfirmation($output, '<info>Would you like to create a new user (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($createUser) {
            $name = $this->dialog->askAndValidate(
                $output,
                '<info>Enter the name of the person:</info> ',
                function($input) {
                    if (empty($input)) {
                        throw new InvalidArgumentException('Name cannot be empty');
                    }

                    return $input;
                }
            );

            $this->application->createPerson($name, $output);
            $this->createPersons($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function createTeams(OutputInterface $output)
    {
        $this->application->listTeams($output);
        $createTeam = $this->dialog->askConfirmation($output, '<info>Would you like to create a new team (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($createTeam) {
            $name = $this->dialog->askAndValidate(
                $output,
                '<info>Enter the name of the team:</info> ',
                function($input) {
                    if (empty($input)) {
                        throw new InvalidArgumentException('Name cannot be empty');
                    }

                    return $input;
                }
            );

            $this->application->createTeam($name, $output);
            $this->createTeams($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function addTeamMember(OutputInterface $output)
    {
        $this->application->listPersons($output);
        $this->application->listTeams($output);
        $create = $this->dialog->askConfirmation($output, '<info>Would you like to add a user to a team (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($create) {
            $personName = $this->dialog->askAndValidate(
                $output,
                '<info>Enter the name of the person to add:</info> ',
                function($input) {
                    if (empty($input)) {
                        throw new InvalidArgumentException('Name cannot be empty');
                    }

                    return $input;
                }
            );

            $teamName = $this->dialog->askAndValidate(
                $output,
                '<info>Enter the name of the team to join the user to:</info> ',
                function($input) {
                    if (empty($input)) {
                        throw new InvalidArgumentException('Name cannot be empty');
                    }

                    return $input;
                }
            );

            try {
                $this->application->joinTeam($personName, $teamName, $output);
            } catch (\Exception $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }

            $this->addTeamMember($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function manageSprints(OutputInterface $output)
    {
        $option = 999;
        while ($option > 0) {
            $this->application->listSprints($output);
            $options = array(
                1 => 'Create a sprint',
                2 => 'Start sprint',
//                3 => 'Stop sprint',
                0 => 'Back',
            );

            // Show options
            foreach ($options as $key => $optionToShow) {
                $output->writeln("<info>[{$key}] {$optionToShow}</info>");
            }

            // Choose action
            $option = $this->dialog->askAndValidate(
                $output,
                '<info>What operation would you like to do on sprints ?</info> ',
                function($input) use ($options) {
                    if (false === array_key_exists($input, $options)) {
                        throw new InvalidArgumentException('You must choose a valid option.');
                    }

                    return $input;
                }
            );

            // Dispatch
            switch ($option) {
                case 1:
                    $this->createSprint($output);
                    break;

                case 2:
                    $this->startSprint($output);
                    break;
            }
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function createSprint(OutputInterface $output)
    {
        $sprintName = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the sprint:</info> ',
            function($input) {
                if (empty($input)) {
                    throw new InvalidArgumentException('Name cannot be empty');
                }

                return $input;
            }
        );

        $this->application->listTeams($output);
        $teamName = $this->dialog->askAndValidate(
            $output,
            '<info>Which team will does the sprint belongs to ?</info> ',
            function($input) {
                if (empty($input)) {
                    throw new InvalidArgumentException('Name cannot be empty');
                }

                return $input;
            }
        );

        try {
            $this->application->createSprint($sprintName, $teamName, $output);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        $createSprint = $this->dialog->askConfirmation($output, '<info>Would you like to create a new sprint (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($createSprint) {
            $this->createSprint($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function startSprint(OutputInterface $output)
    {
        $sprints = $this->sprintRepository->findAll();
        $sprintCollection = new SprintCollection($sprints);
        $sprintName = $this->askQuestion(new StartSprintQuestion($sprintCollection));

        $persons = $this->personRepository->findAll();

        $addNewUser = true;
        while ($addNewUser) {
            $personName = $this->askQuestion(new JoinSprintQuestion(new PersonCollection($persons)));

            try {
                $this->application->joinSprint(
                    $sprintName,
                    $personName,
                    $this->askQuestion(new AskManDaysQuestion()),
                    $this->output
                );

                $addNewUser = $this->askQuestion(new ConfirmationQuestion('Do you want to add a new user to the sprint ? '));
            } catch (\Exception $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
        }

        $sprints = $this->sprintRepository->findAll();
        $sprintCollection = new SprintCollection($sprints);
        $sprint = $this->sprintRepository->findOneByName($sprintName);
        $calculator = new ResourceCalculator();

        try {
            $this->application->startSprint(
                $sprintName,
                $calculator->calculateEstimatedVelocity($sprint->getManDays(), $sprintCollection),
                $this->output
            );
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    /**
     * @param Question $question
     *
     * @return string
     */
    private function askQuestion(Question $question)
    {
        return $this->question->ask($this->input, $this->output, $question);
    }
}
 