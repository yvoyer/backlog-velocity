<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Cli\Commands;

use Star\BacklogVelocity\Agile\Application\Command\Project\JoinTeam as JoinTeamCommand;
use Star\BacklogVelocity\Agile\Application\Command\Project\JoinTeamHandler;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\BacklogException;
use Star\BacklogVelocity\Agile\Domain\Model\Exception\InvalidArgumentException;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\BacklogVelocity\Agile\Domain\Model\TeamRepository;
use Star\BacklogVelocity\Cli\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinTeam extends Command
{
    const ARGUMENT_TEAM = 'team';
    const ARGUMENT_PERSON = 'person';

    const NAME = 'backlog:team:join';

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @param TeamRepository $teamRepository
     * @param PersonRepository $personRepository
     */
    public function __construct(TeamRepository $teamRepository, PersonRepository $personRepository)
    {
        parent::__construct(self::NAME);
        $this->teamRepository = $teamRepository;
        $this->personRepository = $personRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Link a person to a team.');
        $this->addArgument(self::ARGUMENT_PERSON, InputArgument::REQUIRED, 'Specify the person name.');
        $this->addArgument(self::ARGUMENT_TEAM, InputArgument::REQUIRED, 'Specify the team.');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @throws InvalidArgumentException
     * @return null|integer null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $personName = $input->getArgument(self::ARGUMENT_PERSON);
        $teamName = $input->getArgument(self::ARGUMENT_TEAM);
        $view = new ConsoleView($output);

        if (empty($personName)) {
            throw new InvalidArgumentException('Person name must be supplied');
        }

        if (empty($teamName)) {
            throw new InvalidArgumentException('Team name must be supplied');
        }

        try {
            $personName = new PersonName($personName);
            $handler = new JoinTeamHandler($this->teamRepository, $this->personRepository);
            $handler(new JoinTeamCommand(
                $this->teamRepository->findOneByName($teamName)->getId(),
                $this->personRepository->personWithName($personName)->memberId()
            ));
            $view->renderSuccess("Sprint member '{$personName->toString()}' is now part of team '{$teamName}'.");
        } catch (BacklogException $ex) {
            $view->renderFailure($ex->getMessage());
            return 1;
        }

        return 0;
    }
}
