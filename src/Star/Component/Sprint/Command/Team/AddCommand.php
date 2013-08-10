<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Repository\Team\YamlFileRepository;
use Star\Component\Sprint\Team;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

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
     * @var \Star\Component\Sprint\Repository\Team\YamlFileRepository
     */
    private $objectRepository;

    public function __construct($root)
    {
        parent::__construct();
        $this->objectRepository = new YamlFileRepository($root, 'teams');
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('backlog:team:add');
        $this->setDescription('Add a team');
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
        /**
         * @var $dialog \Symfony\Component\Console\Helper\DialogHelper
         */
        $dialog = $this->getHelperSet()->get('dialog');
        $name   = $dialog->ask($output, '<question>Enter the team name: </question>');

        $team = new Team($name);
        $this->objectRepository->save($team);
    }
}
