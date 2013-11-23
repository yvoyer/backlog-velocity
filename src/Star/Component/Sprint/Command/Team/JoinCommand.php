<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Team;

use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Repository\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
    const OPTION_TEAM = 'team';
    const OPTION_SPRINTER = 'sprinter';

    const NAME = 'backlog:team:join';

    /**
     * @var EntityCreator
     */
    private $creator;

    /**
     * @var EntityFinder
     */
    private $finder;

    /**
     * @var TeamMemberRepository
     */
    private $repository;

    /**
     * @param EntityCreator $creator
     * @param EntityFinder  $finder
     * @param Repository    $repository
     */
    public function __construct(
        EntityCreator $creator,
        EntityFinder $finder,
        Repository $repository
    ) {
        parent::__construct(self::NAME);
        $this->creator    = $creator;
        $this->finder     = $finder;
        $this->repository = $repository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Link a sprinter to a team.');
        $this->addOption(self::OPTION_SPRINTER, null, InputOption::VALUE_REQUIRED, 'Specify the sprinter');
        $this->addOption(self::OPTION_TEAM, null, InputOption::VALUE_REQUIRED, 'Specify the team');
        $this->addOption('force', null, InputOption::VALUE_NONE, 'Force the creation of team or sprint if not already created');
        $this->addOption('man-days', null, InputOption::VALUE_REQUIRED, 'The available man days for the team');
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
        $sprinterName = $input->getOption(self::OPTION_SPRINTER);
        $teamName     = $input->getOption(self::OPTION_TEAM);

        if (empty($sprinterName)) {
            throw new \InvalidArgumentException('Sprinter name must be supplied');
        }

        if (empty($teamName)) {
            throw new \InvalidArgumentException('Team name must be supplied');
        }

        $team = $this->finder->findTeam($teamName);
        if (null === $team && $input->getOption('force')) {
            $team = $this->creator->createTeam($teamName);
        }

        if (null === $team) {
            throw new \InvalidArgumentException('The team could not be found.');
        }

        $sprinter = $this->finder->findSprinter($sprinterName);
        if (null === $sprinter && $input->getOption('force')) {
            $sprinter = $this->creator->createSprinter($sprinterName);
        }

        if (null === $sprinter) {
            throw new \InvalidArgumentException('The sprinter could not be found.');
        }

        $this->repository->add($team);
        // @todo manage available man days
        $this->repository->add($team->addMember($sprinter, -1));
        $this->repository->add($sprinter);
        $this->repository->save();

        $output->writeln("Sprinter '{$sprinterName}' is now part of team '{$teamName}'.");
    }
}
