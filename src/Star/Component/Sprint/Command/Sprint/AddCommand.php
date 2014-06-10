<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
 */
class AddCommand extends Command
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @param TeamRepository $teamRepository
     * @param SprintRepository $sprintRepository
     */
    public function __construct(TeamRepository $teamRepository, SprintRepository $sprintRepository)
    {
        parent::__construct('backlog:sprint:add');

        $this->teamRepository = $teamRepository;
        $this->sprintRepository = $sprintRepository;
    }

    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the sprint.');
        $this->addArgument('team', InputArgument::REQUIRED, 'The team name to link the sprint to.');
        $this->setDescription('Create a new sprint for the team.');
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
     *
     * @throws \LogicException When this abstract method is not implemented
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sprintName = $input->getArgument('name');
        $teamName   = $input->getArgument('team');

        $team = $this->teamRepository->findOneByName($teamName);
        if (null === $team) {
            $output->writeln("<error>The team '{$teamName}' cannot be found.</error>");
            return 1;
        }

        $sprint = $team->createSprint($sprintName);
        $this->sprintRepository->add($sprint);
        $this->sprintRepository->save();

        $output->writeln('The object was successfully saved.');
    }
}
