<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Application\Cli\Commands;

use Star\Component\Sprint\Entity\Repository\Filters\AllObjects;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Exception\DeprecatedFeatureException;
use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;
use Star\Component\Sprint\Model\ManDays;
use Star\Component\Sprint\Model\PersonName;
use Star\Component\Sprint\Model\SprintName;
use Star\Component\Sprint\Template\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class JoinSprint extends Command
{
    const ARGUMENT_SPRINT = 'sprint';
    const ARGUMENT_PERSON = 'person';
    const ARGUMENT_MAN_DAYS = 'man-days';
    const ARGUMENT_PROJECT = 'project';

    /**
     * @var SprintRepository
     */
    private $sprintRepository;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @param SprintRepository $sprintRepository
     * @param PersonRepository $personRepository
     */
    public function __construct(SprintRepository $sprintRepository, PersonRepository $personRepository)
    {
        parent::__construct('backlog:sprint:join');

        $this->sprintRepository = $sprintRepository;
        $this->personRepository = $personRepository;
    }

    protected function configure()
    {
        $this->setDescription('Join a team member to a sprint.');
        $this->addArgument(self::ARGUMENT_SPRINT, InputArgument::REQUIRED, 'The sprint name');
        $this->addArgument(self::ARGUMENT_PERSON, InputArgument::REQUIRED, 'The sprinter name');
        $this->addArgument(self::ARGUMENT_MAN_DAYS, InputArgument::REQUIRED, 'The man days the user estimated');
        $this->addArgument(self::ARGUMENT_PROJECT, InputArgument::REQUIRED, 'The project name where the sprint should be.');
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
        $projectId = $input->getArgument(self::ARGUMENT_PROJECT);
        $view = new ConsoleView($output);

        $sprint = $this->sprintRepository->sprintWithName(
            ProjectId::fromString($projectId),
            new SprintName($sprintName)
        );
        if (null === $sprint) {
            $view->renderFailure("The sprint '{$sprintName}' can't be found.");
            return 1;
        }

        $person = $this->personRepository->personWithName(new PersonName($personName));
        if (null === $person) {
            $view->renderFailure("The person with name '{$personName}' can't be found.");
            return 1;
        }

        $sprint->commit($person->getId(), ManDays::fromInt($availableManDays));
        $this->sprintRepository->saveSprint($sprint);

        $view->renderSuccess(
            "The person '{$personName}' is now committed to the sprint '{$sprintName}' of project '{$projectId}' for {$availableManDays} man days."
        );
        return 0;
    }
}