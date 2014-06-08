<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Person;

use Star\Component\Sprint\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddPersonCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Person
 */
class AddPersonCommand extends Command
{
    /**
     * The object repository.
     *
     * @var PersonRepository
     */
    private $repository;

    /**
     * @var TeamFactory
     */
    private $factory;

    /**
     * @param PersonRepository $repository
     * @param TeamFactory  $creator
     */
    public function __construct(PersonRepository $repository, TeamFactory $creator)
    {
        parent::__construct('backlog:person:add');
        $this->repository = $repository;
        $this->factory = $creator;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a person.');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the person to add');
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
        $personName = $input->getArgument('name');

        if (null === $this->repository->findOneByName($personName)) {
            $person = $this->factory->createPerson($personName);
            $this->repository->add($person);
            $this->repository->save();
            $output->writeln("<info>The person '{$personName}' was successfully saved.</info>");
            return 0;
        }

        $output->writeln("<error>The person '{$personName}' already exists.</error>");
        return 1;
    }
}
 