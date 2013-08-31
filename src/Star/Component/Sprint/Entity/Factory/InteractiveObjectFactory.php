<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Member;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\MemberInterface;
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

    public function __construct()
    {
        $this->dialog = new NullDialog();
        $this->output = new NullOutput();
    }

    /**
     * Configure the factory to use interactive objects.
     *
     * @param DialogHelper    $dialog
     * @param OutputInterface $output
     */
    public function setup(DialogHelper $dialog, OutputInterface $output)
    {
        $this->dialog = $dialog;
        $this->output = $output;
    }

    /**
     * Create a member object.
     *
     * @return MemberInterface
     */
    public function createMember()
    {
        $member = new Member();

        return $member;
    }

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
     */
    public function createSprint()
    {
        $name   = $this->askQuestion('Enter the sprint name: ');
        $sprint = new Sprint($name);

        return $sprint;
    }

    /**
     * Create a team object.
     *
     * @return TeamInterface
     */
    public function createTeam()
    {
        $name = $this->askQuestion('Enter the team name: ');
        $team = new Team($name);

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
}
