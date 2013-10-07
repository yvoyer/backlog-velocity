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
use Star\Component\Sprint\Mapping\Entity;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\SprintMemberData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Null\NullDialog;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InteractiveObjectFactory
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Factory
 */
class InteractiveObjectFactory implements EntityCreatorInterface
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
     * @param DialogHelper $dialog
     */
    public function __construct(DialogHelper $dialog)
    {
        $this->dialog = $dialog;
        $this->output = new NullOutput();
    }

    /**
     * Set the output format.
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Create a sprint object.
     *
     * @return Sprint
     */
    public function createSprint()
    {
        $name   = $this->askQuestion('Enter the sprint name: ');
        $sprint = new SprintData($name, new TeamData(''));

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
        $name = $this->askQuestion('Enter the team name: ');
        $team = new TeamData($name);

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
     * @return SprintMember
     */
    public function createSprintMember()
    {
        $availableManDays = $this->askQuestion('Enter the available man days for the sprint: ');
        // @todo Tests
        $team = new SprintMemberData($availableManDays, null, $this->createSprint(), $this->createTeamMember());

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
        $name = $this->askQuestion("Enter the sprinter's name: ");
        $team = new SprinterData($name);

        return $team;
    }

    /**
     * Create a TeamMember.
     *
     * @return TeamMember
     */
    public function createTeamMember()
    {
        $teamMember = new TeamMemberData($this->createSprinter(''), $this->createTeam(''));

        return $teamMember;
    }

    /**
     * Create an object of $type.
     *
     * @param string $type
     *
     * @throws \InvalidArgumentException
     * @return Entity
     */
    public function createObject($type)
    {
        $object = null;
        switch ($type)
        {
            case EntityCreatorInterface::TYPE_SPRINT:
                $object = $this->createSprint();
                break;
            case EntityCreatorInterface::TYPE_SPRINTER:
                $object = $this->createSprinter('');
                break;
            case EntityCreatorInterface::TYPE_SPRINT_MEMBER:
                $object = $this->createSprintMember();
                break;
            case EntityCreatorInterface::TYPE_TEAM:
                $object = $this->createTeam('');
                break;
            case EntityCreatorInterface::TYPE_TEAM_MEMBER:
                $object = $this->createTeamMember();
                break;
            default:
                throw new \InvalidArgumentException("The type '{$type}' is not supported.");
        }

        return $object;
    }
}
