<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Command\Sprinter;

use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JoinTeamCommand
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Command\Sprinter
 */
class JoinTeamCommand extends Command
{
    const OPTION_TEAM = 'team';
    const OPTION_SPRINTER = 'sprinter';

    const NAME = 'backlog:sprinter:join-team';

    /**
     * @var SprinterRepository
     */
    private $sprinterRepository;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    public function __construct(SprinterRepository $sprinterRepository, TeamRepository $teamRepository)
    {
        parent::__construct(self::NAME);

        $this->sprinterRepository = $sprinterRepository;
        $this->teamRepository     = $teamRepository;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Link a sprinter to a team.');
        $this->addOption(self::OPTION_SPRINTER, null, InputOption::VALUE_REQUIRED, 'Specify the sprinter');
        $this->addOption(self::OPTION_TEAM, null, InputOption::VALUE_REQUIRED, 'Specify the team');
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

        $team     = $this->teamRepository->findOneByName($teamName);
        $sprinter = $this->sprinterRepository->findOneByName($sprinterName);

        $this->teamRepository->add($team->addMember($sprinter));
        $this->teamRepository->save();

        $output->writeln("Sprinter '{$sprinterName}' is now part of team '{$teamName}'.");
    }
}
