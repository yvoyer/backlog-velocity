<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Plugin\Null\Entity\NullSprint;
use Star\Plugin\Null\Entity\NullTeamMember;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Class InteractiveObjectFactory
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class InteractiveObjectFactory implements EntityCreator
{
    /**
     * @var \Symfony\Component\Console\Helper\DialogHelper
     */
    private $dialog;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var EntityCreator
     */
    private $objectCreator;

    /**
     * @param DialogHelper    $dialog
     * @param OutputInterface $output
     * @param EntityCreator $objectCreator todo remove null
     *
     * @todo Add EntityCreator in DI for decorating
     * @todo Inject Finder, so that if the supplied name already exists, it do not create
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output, EntityCreator $objectCreator = null)
    {
        $this->dialog = $dialog;
        $this->output = $output;

        if (null === $objectCreator) {
            $objectCreator = new BacklogModelCreator();
        }
        $this->objectCreator = $objectCreator;
    }

    /**
     * Create a sprint object.
     *
     * @param string $name
     * @param string $teamName
     *
     * @return Sprint
     */
    public function createSprint($name, $teamName)
    {
        // @todo Use another way to not inject instead
        $team = $this->objectCreator->createTeam($teamName);
        $sprint = $this->objectCreator->createSprint($name, $team);

        if (false === $sprint->isValid()) {
            $name   = $this->askQuestion('Enter the sprint name: ');
            $sprint = $this->createSprint($name, $teamName);
        }

        return $sprint;
    }

    /**
     * Create a team object.
     *
     * @param string $name
     *
     * @return Team
     */
    public function createTeam($name)
    {
        $team = $this->objectCreator->createTeam($name);

        if (false === $team->isValid()) {
            $name = $this->askQuestion('Enter the team name: ');
            $team = $this->createTeam($name);
        }

        return $team;
    }

    /**
     * Ask a question and return the answer.
     *
     * @param $question
     *
     * @return string
     */
    private function askQuestion($question)
    {
        return $this->dialog->ask($this->output, '<question>' . $question . '</question>');
    }

    /**
     * Create a SprintMember.
     *
     * @param integer    $availableManDays
     * @param integer    $actualVelocity
     * @param Sprint     $sprint
     * @param TeamMember $teamMember
     *
     * @return SprintMember
     */
    public function createSprintMember($availableManDays, $actualVelocity, Sprint $sprint, TeamMember $teamMember)
    {
        $availableManDays = $this->askQuestion('Enter the available man days for the sprint: ');
        // @todo Tests
        $team = $this->objectCreator->createSprintMember($availableManDays, null, new NullSprint(), new NullTeamMember());

        return $team;
    }

    /**
     * Create a Sprinter.
     *
     * @param string $name
     *
     * @return Sprinter
     */
    public function createSprinter($name)
    {
        $sprinter = $this->objectCreator->createSprinter($name);

        if (false === $sprinter->isValid()) {
            $name     = $this->askQuestion("Enter the sprinter's name: ");
            $sprinter = $this->createSprinter($name);
        }

        return $sprinter;
    }

    /**
     * Create a TeamMember.
     *
     * @param Sprinter $sprinter
     * @param Team     $team
     * @param integer  $availableManDays
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team, $availableManDays)
    {
        $teamMember = $this->objectCreator->createTeamMember($sprinter, $team, $availableManDays);

        return $teamMember;
    }
}
