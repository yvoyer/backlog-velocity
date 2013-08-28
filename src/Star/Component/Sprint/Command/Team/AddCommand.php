<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
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
     * @var \Star\Component\Sprint\Entity\Repository\TeamRepository
     */
    private $objectRepository;

    public function __construct(TeamRepository $objectRepository)
    {
        parent::__construct('backlog:team:add');
        $this->objectRepository = $objectRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
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

        $object = $this->createObject($dialog, $output);
        $this->objectRepository->add($object);
        $this->objectRepository->save();

        $output->writeln('The object was successfully saved.');
    }

    /**
     * Create the object.
     * @todo Refactor to use a factory for multiple objects (createTeam, createSprint, CreateMember, etc.)
     *
     * @param \Symfony\Component\Console\Helper\DialogHelper    $dialog
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return Team
     */
    private function createObject(DialogHelper $dialog, OutputInterface $output)
    {
        $name = $dialog->ask($output, '<question>Enter the team name: </question>');
        $team = new Team($name);

        return $team;
    }
}
