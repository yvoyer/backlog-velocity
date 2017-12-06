<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Domain\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Domain\Entity\Team;
use Star\Component\Sprint\Domain\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class ListTeams extends Command
{
    /**
     * @var \Star\Component\Sprint\Domain\Entity\Repository\TeamRepository
     */
    private $repository;

    public function __construct(TeamRepository $sprintRepository)
    {
        parent::__construct('backlog:team:list');
        $this->repository = $sprintRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('List the teams.');
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
        $view = new ConsoleView($output);
        /**
         * @var $teams Team[]
         */
        $teams = $this->repository->allTeams();
        $view->renderHeaderTemplate("List of team's details:");

        $table = new Table($output);
        $table->setHeaders(array('Team', 'Members'));

        $elements = array();
        $teamCount = count($teams);
        $i = 0;
        foreach ($teams as $team) {
            $i ++;

            $teamMembers = $team->getTeamMembers();
            $table->addRow(array('<comment>' . $team->getName()->toString() . '</comment>'));
            foreach ($teamMembers as $teamMember) {
                $table->addRow(array('', $teamMember->toString()));
            }

            if ($i < $teamCount) {
                $table->addRow(new TableSeparator());
            }
        }

        $table->render();
        $view->renderListTemplate($elements);
    }
}
