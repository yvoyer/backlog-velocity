<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Team
 */
class AddCommand extends Command
{
    /**
     * The object repository.
     *
     * @var Repository
     */
    private $repository;

    /**
     * @var TeamFactory
     */
    private $factory;

    /**
     * @param TeamRepository $repository
     * @param TeamFactory    $factory
     */
    public function __construct(TeamRepository $repository, TeamFactory $factory)
    {
        parent::__construct('backlog:team:add');
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a team');
        $this->addArgument('name', InputArgument::OPTIONAL, 'The name of the team to add');
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
        $message = 'The team already exists.';
        $teamName = $input->getArgument('name');
        $team = $this->factory->createTeam($teamName);

        if (null === $this->repository->findOneByName($team->getName())) {
            $this->repository->add($team);
            $this->repository->save();
            $message = 'The object was successfully saved.';
        }

        $output->writeln($message);
    }
}
