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
use Star\Component\Sprint\Template\ConsoleView;
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
        $this->setDescription('Add a team.');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the team to add');
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
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $teamName = $input->getArgument('name');
        $view = new ConsoleView($output);

        if (null === $this->repository->findOneByName($teamName)) {
            $team = $this->factory->createTeam($teamName);
            $this->repository->add($team);
            $this->repository->save();
            $view->renderSuccess("The team '{$teamName}' was successfully saved.");
            return 0;
        }

        $view->renderFailure("The team '{$teamName}' already exists.");
        return 1;
    }
}
