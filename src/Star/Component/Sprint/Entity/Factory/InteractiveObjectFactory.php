<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Null\NullSprint;
use Star\Component\Sprint\Entity\Null\NullTeamMember;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\SprintMember;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\TeamMember;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
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
     * @param DialogHelper    $dialog
     * @param OutputInterface $output
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output)
    {
        $this->dialog = $dialog;
        $this->output = $output;
    }

    /**
     * Create a sprint object.
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function createSprint($name)
    {
        $sprint = new SprintData($name, new TeamData(''));

        if (false === $sprint->isValid()) {
            $name   = $this->askQuestion('Enter the sprint name: ');
            $sprint = $this->createSprint($name);
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
        $team = new TeamData($name);

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
        $team = new SprintMemberData($availableManDays, null, new NullSprint(), new NullTeamMember());

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
        $sprinter = new SprinterData($name);

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
     *
     * @return TeamMember
     */
    public function createTeamMember(Sprinter $sprinter, Team $team)
    {
        $teamMember = new TeamMemberData($sprinter, $team);

        return $teamMember;
    }
}
