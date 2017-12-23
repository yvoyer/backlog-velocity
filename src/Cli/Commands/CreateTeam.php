<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Domain\Model\TeamId;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamName;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class CreateTeam extends Command
{
    /**
     * The object repository.
     *
     * @var TeamRepository
     */
    private $repository;

    /**
     * @param TeamRepository $repository
     */
    public function __construct(TeamRepository $repository, \Exception $projects = null)
    {
        parent::__construct('backlog:team:add');
        $this->repository = $repository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add a team.');
        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the team to add'
        );
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
        $teamName = $input->getArgument('name');
        $view = new ConsoleView($output);

        if ($this->repository->teamWithNameExists(new TeamName($teamName))) {
            $view->renderFailure("The team '{$teamName}' already exists.");
            return 1;
        }

        $team = TeamModel::create(TeamId::fromString($teamName), new TeamName($teamName));
        $this->repository->saveTeam($team);
        $view->renderSuccess("The team '{$teamName}' was successfully saved.");
        return 0;
    }
}