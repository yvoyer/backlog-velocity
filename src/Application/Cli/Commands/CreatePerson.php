<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Domain\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Model\PersonName;
use Star\Component\Sprint\Domain\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreatePerson extends Command
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
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $view = new ConsoleView($output);
        $personName = $input->getArgument('name');

        if ($this->repository->personWithNameExists(new PersonName($personName))) {
            $view->renderFailure("The person '{$personName}' already exists.");
            return 1;
        }

        $person = $this->factory->createPerson($personName);
        $this->repository->savePerson($person);
        $view->renderSuccess("The person '{$personName}' was successfully saved.");
        return 0;
    }
}
