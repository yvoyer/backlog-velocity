<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\SprintCommitment;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprint
 */
class ListCommand extends Command
{
    /**
     * @var SprintRepository
     */
    private $repository;

    public function __construct(SprintRepository $sprintRepository)
    {
        parent::__construct('backlog:sprint:list');
        $this->repository = $sprintRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('List all available sprints.');
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
         * @var $sprints Sprint[]
         */
        $sprints = $this->repository->findAll();
        $view = new ConsoleView($output);

        $view->renderHeaderTemplate('List of available sprints:');
        if (empty($sprints)) {
            $view->renderNotice('No sprints were found.');
        }

        $table = new Table($output);
        $table->setHeaders(array('Sprint', 'Members', 'Commitment'));
        $sprintCount = count($sprints);
        $i = 0;
        foreach ($sprints as $sprint) {
            /**
             * @var $sprintMembers SprintCommitment[]
             */
            $sprintMembers = $sprint->getCommitments();
            $table->addRow(array('<comment>' . $sprint->getName() . '</comment>'));
            foreach ($sprintMembers as $sprintMember) {
                $table->addRow(array('', $sprintMember->getName(), $sprintMember->getAvailableManDays()));
            }

            $i ++;
            if ($i < $sprintCount) {
                $table->addRow(new TableSeparator());
            }
        }

        $table->render();
    }
}
