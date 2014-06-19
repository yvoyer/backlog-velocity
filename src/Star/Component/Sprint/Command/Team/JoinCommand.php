<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Exception\InvalidArgumentException;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JoinCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Team
 */
class JoinCommand extends Command
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
     * @var TeamMemberRepository
     */
    private $teamMemberRepository;

    /**
     * @param TeamRepository $teamRepository
     * @param PersonRepository $personRepository
     * @param TeamMemberRepository $teamMemberRepository
     */
    public function __construct(
        TeamRepository $teamRepository,
        PersonRepository $personRepository,
        TeamMemberRepository $teamMemberRepository
    ) {
        parent::__construct(self::NAME);
        $this->teamRepository = $teamRepository;
        $this->personRepository = $personRepository;
        $this->teamMemberRepository = $teamMemberRepository;
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
     * @throws \Star\Component\Sprint\Exception\InvalidArgumentException
     * @throws \Star\Component\Sprint\Exception\EntityNotFoundException
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

        $team = $this->teamRepository->findOneByName($teamName);
        if (null === $team) {
            throw new EntityNotFoundException('The team could not be found.');
        }

        $person = $this->personRepository->findOneByName($personName);
        if (null === $person) {
            throw new EntityNotFoundException('The person could not be found.');
        }

        $teamMember = $team->addTeamMember($person);

        $this->teamMemberRepository->add($teamMember);
        $this->teamMemberRepository->save();

        $view->renderSuccess("Sprint member '{$personName}' is now part of team '{$teamName}'.");
    }
}
