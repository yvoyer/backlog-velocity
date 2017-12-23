<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\ProjectName;
use Star\Component\Sprint\Model\TeamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class RunCommand extends Command
{
    /**
     * @var BacklogApplication
     */
    private $application;

    /**
     * @var DialogHelper
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
                $createProject = 1 => 'Create project',
                $createPerson = 2 => 'Create person',
                $createTeam = 3 => 'Create team',
                $addPersonToTeam = 4 => 'Assign person to a team',
                $commit = 5 => 'Commit sprint member',
                $createSprint = 6 => 'Create sprint',
                $startSpriunt = 7 => 'Start sprint',
                $endSprint = 8 => 'End sprint',
                $exit = 0 => 'Exit',
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
                case $createProject:
                    $this->createProject($output);
                    break;

                case $createPerson:
                    $this->createPersons($output);
                    break;

                case $createTeam:
                    $this->createTeams($output);
                    break;

                case $addPersonToTeam:
                    $this->addTeamMember($output);
                    break;

                default:
                    throw new \RuntimeException("Action '{$options[$option]} ({$option})' not implemented yet.");
            }
        }

        $output->writeln('Bye');
    }

    private function createProject(OutputInterface $output)
    {
        // todo list available projects
        /**
         * @var ProjectName $name
         */
        $name = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the project:</info> ',
            function($input) {
                return new ProjectName($input);
            }
        );

        $this->application->createProject($name->toString(), $output);
        $createAnother = $this->dialog->askConfirmation($output, '<info>Would you like to create another project (yes/no) ?</info>  <comment>[no]</comment>: ', false);
        if ($createAnother) {
            $this->createProject($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function createPersons(OutputInterface $output)
    {
        $this->application->listPersons($output);
        /**
         * @var PersonName $name
         */
        $name = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the person:</info> ',
            function($input) {
                return new PersonName($input);
            }
        );

        $this->application->createPerson($name->toString(), $output);
        $createUser = $this->dialog->askConfirmation($output, '<info>Would you like to create another person (yes/no) ?</info>  <comment>[no]</comment>: ', false);

        if ($createUser) {
            $this->createPersons($output);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function createTeams(OutputInterface $output)
    {
        $this->application->listTeams($output);
        /**
         * @var TeamName $name
         */
        $name = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the team:</info> ',
            function($input) {
                return new TeamName($input);
            }
        );
        $this->application->createTeam($name->toString(), $output);

        $createTeam = $this->dialog->askConfirmation($output, '<info>Would you like to create another team (yes/no) ?</info>  <comment>[no]</comment>: ', false);
        if ($createTeam) {
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
        /**
         * @var PersonName $personName
         */
        $personName = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the person to add:</info> ',
            function($input) {
                return new PersonName($input);
            }
        );

        /**
         * @var TeamName $teamName
         */
        $teamName = $this->dialog->askAndValidate(
            $output,
            '<info>Enter the name of the team to join to:</info> ',
            function($input) {
                return new TeamName($input);
            }
        );

        $this->application->joinTeam($personName->toString(), $teamName->toString(), $output);

        $create = $this->dialog->askConfirmation($output, '<info>Is there another person that needs to join the team (yes/no) ?</info>  <comment>[no]</comment>: ', false);
        if ($create) {
            $this->addTeamMember($output);
        }
    }
}
