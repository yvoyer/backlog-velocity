<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Person;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListPersonCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Person
 */
class ListPersonCommand extends Command
{
    /**
     * @var PersonRepository
     */
    private $repository;

    public function __construct(PersonRepository $repository)
    {
        parent::__construct('backlog:person:list');
        $this->repository = $repository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('List the teams');
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
        /**
         * @var $result Person[]
         */
        $result = $this->repository->findAll();

        foreach ($result as $team) {
            $output->writeln($team->getName());
        }
    }
}
 