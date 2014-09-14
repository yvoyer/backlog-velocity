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
use Star\Component\Sprint\Collection\SprintMemberCollection;
use Star\Component\Sprint\Collection\TeamCollection;
use Star\Component\Sprint\Command\Question\AskManDaysQuestion;
use Star\Component\Sprint\Command\Question\GetNameInputQuestion;
use Star\Component\Sprint\Command\Question\JoinSprintQuestion;
use Star\Component\Sprint\Command\Question\SelectTeamQuestion;
use Star\Component\Sprint\Command\Question\StartSprintQuestion;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
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

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var SprintMemberRepository
     */
    private $sprintMemberRepository;

    public function __construct(
        BacklogApplication $application,
        SprintRepository $sprintRepository,
        PersonRepository $personRepository,
        TeamRepository $teamRepository,
        SprintMemberRepository $sprintMemberRepository
    ) {
        $this->application = $application;
        $this->sprintRepository = $sprintRepository;
        $this->personRepository = $personRepository;
        $this->teamRepository = $teamRepository;
        $this->sprintMemberRepository = $sprintMemberRepository;

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
                    $this->createPersonAction($output);
                    break;

                case 2:
                    $this->createTeamAction($output);
                    break;

                case 3:
                    $this->addTeamMemberAction($output);
                    break;

                case 4:
                    $this->startSprintAction($output);
                    break;
            }
        }

        $output->writeln('Bye');
    }

    /**
     * @param OutputInterface $output
     */
    private function createPersonAction(OutputInterface $output)
    {
        $this->application->listPersons($output);
        $name = $this->askQuestion(new GetNameInputQuestion('Enter the name of the person:'));

        try {
            $this->application->createPerson($name, $output);
        } catch (\Exception $e) {
            $this->outputError($e->getMessage());
        }

        $createUser = $this->dialog->askConfirmation($output, '<info>Would you like to create a new user (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($createUser) {
            $this->createPersonAction($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function createTeamAction(OutputInterface $output)
    {
        $this->application->listTeams($output);
        $teamName = $this->askQuestion(new GetNameInputQuestion('Enter the name of the team:'));

        try {
            $this->application->createTeam($teamName, $output);
        } catch (\Exception $e) {
            $this->outputError($e->getMessage());
        }

        $createTeam = $this->dialog->askConfirmation($output, '<info>Would you like to create a new team (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($createTeam) {
            $this->createTeamAction($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function addTeamMemberAction(OutputInterface $output)
    {
        $this->application->listPersons($output);
        $personName = $this->askQuestion(new GetNameInputQuestion('Enter the name of the person to add:'));

        $this->application->listTeams($output);
        $teamName = $this->askQuestion(new GetNameInputQuestion('Enter the name of the team to join the user to:'));

        try {
            $this->application->joinTeam($personName, $teamName, $output);
        } catch (\Exception $e) {
            $this->outputError($e->getMessage());
        }

        $create = $this->dialog->askConfirmation($output, '<info>Would you like to add a user to a team (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
        if ($create) {
            $this->addTeamMemberAction($output);
        }
    }
//
//    /**
//     * @param OutputInterface $output
//     */
//    private function startSprintAction(OutputInterface $output)
//    {
//        $option = 999;
//        while ($option > 0) {
//            $this->application->listSprints($output);
//            $options = array(
//                1 => 'Create a sprint',
//                2 => 'Start sprint',
////                3 => 'Stop sprint',
//                0 => 'Back',
//            );
//
//            // Show options
//            foreach ($options as $key => $optionToShow) {
//                $output->writeln("<info>[{$key}] {$optionToShow}</info>");
//            }
//
//            // Choose action
//            $option = $this->dialog->askAndValidate(
//                $output,
//                '<info>What operation would you like to do on sprints ?</info> ',
//                function($input) use ($options) {
//                    if (false === array_key_exists($input, $options)) {
//                        throw new InvalidArgumentException('You must choose a valid option.');
//                    }
//
//                    return $input;
//                }
//            );
//
//            // Dispatch
//            switch ($option) {
//                case 1:
//                    $this->createSprint($output);
//                    break;
//
//                case 2:
//                    $this->startSprint($output);
//                    break;
//            }
//        }
//    }

    /**
     * @param OutputInterface $output
     * @param \Star\Component\Sprint\Entity\Team $team
     */
    private function createSprint(OutputInterface $output, Team $team = null)
    {
        $teamName = '';
        if (null === $team) {
            $teamCollection = new TeamCollection($this->teamRepository->findAll());
            $teamName = $this->askQuestion(new SelectTeamQuestion($teamCollection, 'Which team does the sprint belongs to ?'));
        } else {
            $teamName = $team->getName();
        }

        $sprintName = $this->askQuestion(new GetNameInputQuestion('Enter the name of the sprint:'));
//
//        $this->application->listTeams($output);

        try {
            $this->application->createSprint($sprintName, $teamName, $output);
        } catch (\Exception $e) {
            $this->outputError($e->getMessage());
        }
//
//        $createSprint = $this->dialog->askConfirmation($output, '<info>Would you like to create a new sprint (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
//        if ($createSprint) {
//            $this->createSprint($output);
//        }
    }

    /**
     * @param OutputInterface $output
     * @param \Star\Component\Sprint\Entity\Team $team
     */
    private function startSprintAction(OutputInterface $output, Team $team = null)
    {
        $teamCollection = new TeamCollection($this->teamRepository->findAll());
        if ($teamCollection->isEmpty()) {
            $output->writeln('There is no team, you need to create a new one.');
            $this->createTeamAction($output);
            return;
        }

        if (null === $team) {
            $teamName = $this->askQuestion(new SelectTeamQuestion($teamCollection));
            $team = $teamCollection->findOneByName($teamName);
        }

        $sprints = $this->sprintRepository->findNotStartedSprintsOfTeam($team);
        $sprintCollection = new SprintCollection($sprints);

        if ($sprintCollection->isEmpty()) {
            $output->writeln('There is no sprint associated to the team, you need to create a new one.');
            $this->createSprint($output, $team);
            $this->startSprintAction($output, $team);
            return;
        }

        $sprintName = $this->askQuestion(new StartSprintQuestion($sprintCollection));
        $sprint = $sprintCollection->findOneByName($sprintName);

        $sprintMemberCollection = new SprintMemberCollection($this->sprintMemberRepository->findAllMemberNotPartOfSprint($sprint));
        $addNewUser = true;
        while ($addNewUser) {
            $sprintMemberName = $this->askQuestion(new JoinSprintQuestion($sprintMemberCollection));

            try {
                $this->application->joinSprint(
                    $sprintName,
                    $sprintMemberName,
                    $this->askQuestion(new AskManDaysQuestion()),
                    $this->output
                );
            } catch (\Exception $e) {
                $this->outputError($e->getMessage());
            }

            $addNewUser = $this->askQuestion(new ConfirmationQuestion('Do you want to add a new user to the sprint [yes/no] ? '));
        }

//        $sprints = $this->sprintRepository->findAll();
//        $sprintCollection = new SprintCollection($sprints);
//        $sprint = $this->sprintRepository->findOneByName($sprintName);
//        $calculator = new ResourceCalculator();
//
//        try {
//            $this->application->startSprint(
//                $sprintName,
//                $calculator->calculateEstimatedVelocity($sprint->getManDays(), $sprintCollection),
//            );
//        } catch (\Exception $e) {
//            $this->outputError($e->getMessage());
//        }
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

    /**
     * @param $message
     */
    private function outputError($message)
    {
        $this->output->writeln('<error>' . $message . '</error>');
    }
}
 