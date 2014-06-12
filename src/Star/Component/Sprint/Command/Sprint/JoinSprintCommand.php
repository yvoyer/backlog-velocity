<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
    const ARGUMENT_SPRINT = 'sprint';
    const ARGUMENT_PERSON = 'person';
    const ARGUMENT_MAN_DAYS = 'man-days';

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @var TeamMemberRepository
     */
    private $teamMemberRepository;

    public function __construct(SprintRepository $sprintRepository, TeamMemberRepository $teamMemberRepository)
    {
        parent::__construct('backlog:sprint:join');

        $this->sprintRepository = $sprintRepository;
        $this->teamMemberRepository = $teamMemberRepository;
    }

    protected function configure()
    {
        $this->setDescription('Make an person tart of a group.');
        $this->addArgument(self::ARGUMENT_SPRINT, InputArgument::REQUIRED, 'The sprint name');
        $this->addArgument(self::ARGUMENT_PERSON, InputArgument::REQUIRED, 'The sprinter name');
        $this->addArgument(self::ARGUMENT_MAN_DAYS, InputArgument::REQUIRED, 'The man days the user estimated');
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
        $sprintName = $input->getArgument('sprint');
        $personName = $input->getArgument('person');
        $availableManDays = $input->getArgument('man-days');

        $sprint = $this->sprintRepository->findOneByName($sprintName);
        if (null === $sprint) {
            $output->writeln("<error>The sprint '{$sprintName}' can't be found.</error>");
            return 1;
        }

        $teamMember = $this->teamMemberRepository->findMemberOfSprint($personName, $sprintName);
        if (null === $teamMember) {
            $output->writeln("<error>The team's member '{$personName}' is not part of sprint '{$sprintName}'.</error>");
            return 1;
        }

        $sprint->commit($teamMember, $availableManDays);

        $output->writeln("The person '{$personName}' is now committed to the '{$sprintName}' sprint for '{$availableManDays}' man days.");
        return 0;
    }
}
