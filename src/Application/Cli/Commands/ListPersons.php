<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListPersons extends Command
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
        $this->setDescription('List all persons.');
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
        $result = $this->repository->allRegistered();
        $elements = array();
        foreach ($result as $team) {
            $elements[] = $team->getName()->toString();
        }

        $view = new ConsoleView($output);
        $view->renderHeaderTemplate('List of available persons:');
        $view->renderListTemplate($elements);
    }
}
