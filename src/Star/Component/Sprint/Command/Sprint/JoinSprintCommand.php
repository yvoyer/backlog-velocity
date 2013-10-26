<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JoinSprintCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
 */
class JoinSprintCommand extends Command
{
    public function __construct()
    {
        parent::__construct('backlog:sprint:join');
    }

    protected function configure()
    {
        $this->addOption('sprint', 's', InputOption::VALUE_REQUIRED, 'The sprint name');
        $this->addOption('sprinter', 'm', InputOption::VALUE_REQUIRED, 'The sprinter name');
        $this->addOption('team', 't', InputOption::VALUE_REQUIRED, 'The team name');
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
        $sprintName   = $input->getOption('sprint');
        $sprinterName = $input->getOption('sprinter');
        $teamName     = $input->getOption('team');

        throw new \RuntimeException('execute() method not implemented yet.');
    }
}
