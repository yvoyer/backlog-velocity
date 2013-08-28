<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Factory;

use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Entity\TeamInterface;
use Star\Component\Sprint\Entity\SprintInterface;
use Star\Component\Sprint\Entity\MemberInterface;
use Symfony\Component\Console\Helper\DialogHelper;
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
     * @param DialogHelper    $dialog
     * @param OutputInterface $output
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output)
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
        // TODO: Implement createMember() method.
    }

    /**
     * Create a sprint object.
     *
     * @return SprintInterface
     */
    public function createSprint()
    {
        // TODO: Implement createSprint() method.
    }

    /**
     * Create a team object.
     *
     * @return TeamInterface
     */
    public function createTeam()
    {
        $name = $this->dialog->ask($this->output, '<question>Enter the team name: </question>');
        $team = new Team($name);

        return $team;
    }
}
