<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprint;

use Star\Component\Sprint\Entity\Repository\SprintMemberRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Template\ConsoleView;
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

    /**
     * @var SprintMemberRepository
     */
    private $sprintMemberRepository;

    public function __construct(
        SprintRepository $sprintRepository,
        TeamMemberRepository $teamMemberRepository,
        SprintMemberRepository $sprintMemberRepository
    ) {
        parent::__construct('backlog:sprint:join');

        $this->sprintRepository = $sprintRepository;
        $this->teamMemberRepository = $teamMemberRepository;
        $this->sprintMemberRepository = $sprintMemberRepository;
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
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sprintName = $input->getArgument('sprint');
        $personName = $input->getArgument('person');
        $availableManDays = $input->getArgument('man-days');
        $view = new ConsoleView($output);

        $sprint = $this->sprintRepository->findOneByName($sprintName);
        if (null === $sprint) {
            $view->renderFailure("The sprint '{$sprintName}' can't be found.");
            return 1;
        }

        $teamMember = $this->teamMemberRepository->findMemberOfSprint($personName, $sprintName);
        if (null === $teamMember) {
            $view->renderFailure("The team's member '{$personName}' can't be found.");
            return 1;
        }

        $sprintMember = $sprint->commit($teamMember, $availableManDays);
        $this->sprintMemberRepository->add($sprintMember);
        $this->sprintMemberRepository->save();

        $view->renderSuccess("The person '{$personName}' is now committed to the '{$sprintName}' sprint for '{$availableManDays}' man days.");
        return 0;
    }
}
