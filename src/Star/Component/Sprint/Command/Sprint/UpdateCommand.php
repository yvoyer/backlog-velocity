<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
 */
class UpdateCommand extends Command
{
    /**
     * @var SprintRepository
     */
    private $repository;

    /**
     * @param SprintRepository $repository
     */
    public function __construct(SprintRepository $repository)
    {
        parent::__construct('backlog:sprint:update');

        $this->repository = $repository;
    }

    protected function configure()
    {
        $this->setDescription('Update the sprint.');
        $this->addArgument('search', InputArgument::REQUIRED, "The sprint's name to search for.");
        $this->addArgument('name', InputArgument::REQUIRED, 'The new name for the sprint to update.');
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
        $nameToSearch = $input->getArgument('search');
        $newName      = $input->getArgument('name');

        // todo check to find a duplicate name
        $sprint = $this->repository->findOneByName($nameToSearch);

        $message = "Sprint '{$nameToSearch}' was not found.";
        if (null !== $sprint) {
            $sprint->setName($newName);

//            $message = 'The sprint contains invalid data.';
//            if ($sprint->isValid()) {
                $this->repository->add($sprint);
                $this->repository->save();
                $message = 'The sprint was updated successfully.';
//            }
        }

        $output->writeln($message);
    }
}
 