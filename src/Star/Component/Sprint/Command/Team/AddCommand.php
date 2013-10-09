<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Team
 *
 * @deprecated Use ObjectCreatorCommand instead
 */
class AddCommand extends Command
{
    /**
     * The object repository.
     *
     * @var \Star\Component\Sprint\Entity\Repository\TeamRepository
     */
    private $objectRepository;

    /**
     * @var \Star\Component\Sprint\Entity\Factory\EntityCreatorInterface
     */
    private $factory;

    /**
     * @var EntityCreatorInterface
     */
    private $creator;

    /**
     * @param TeamRepository         $objectRepository
     * @param EntityCreatorInterface $factory
     */
    public function __construct(
        TeamRepository $objectRepository,
        EntityCreatorInterface $factory
    ) {
        parent::__construct('backlog:team:add');
        $this->objectRepository = $objectRepository;
        $this->factory          = $factory;
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
        $teamName = $input->getArgument('name');
        $team     = $this->factory->createTeam($teamName);

        $this->objectRepository->add($team);
        $this->objectRepository->save();

        $output->writeln('The object was successfully saved.');
    }
}
