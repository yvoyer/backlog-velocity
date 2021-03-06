<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Cli\BacklogApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class RunCommand extends Command
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var QuestionHelper
     */
    private $dialog;

    public function __construct(BacklogApplication $application)
    {
        $this->application = $application;
        parent::__construct('run');
    }

    protected function configure()
    {
        $this->setDescription('Interactively use the application, in one command.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dialog = $this->getHelper('dialog');

        $option = 999;
        while ($option > 0) {
            $options = array(
                1 => 'Create user',
                2 => 'Create team',
                3 => 'Add team member',
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
        $createUser = $this->dialog->askConfirmation($output, '<info>Would you like to create new users (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
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
        $createTeam = $this->dialog->askConfirmation($output, '<info>Would you like to create new teams (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
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
        $create = $this->dialog->askConfirmation($output, '<info>Would you like to create new team member (yes/no) ?</info>  <comment>[no]</comment>: ', false, 'n');
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
                '<info>Enter the name of the team to join to:</info> ',
                function($input) {
                    if (empty($input)) {
                        throw new InvalidArgumentException('Name cannot be empty');
                    }

                    return $input;
                }
            );

            $this->application->joinTeam($personName, $teamName, $output);
            $this->addTeamMember($output);
        }
    }
}
