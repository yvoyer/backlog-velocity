<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Star\Component\Sprint\Command\Sprint\AddCommand as SprintAddCommand;
use Star\Component\Sprint\Command\Sprint\UpdateCommand as SprintUpdateCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Team\JoinCommand as JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand as TeamList;
use Star\Component\Sprint\Command\Sprint\ListCommand as SprintList;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Repository\RepositoryManager;
use Symfony\Component\Console\Application;

/**
 * Class BacklogApplication
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class BacklogApplication extends Application
{
    /**
     * @param RepositoryManager $repositoryManager
     * @param ObjectManager     $objectManager
     * @param EntityCreator     $objectCreator
     * @param EntityFinder      $objectFinder
     */
    public function __construct(
        RepositoryManager $repositoryManager,
        ObjectManager $objectManager,
        EntityCreator $objectCreator,
        EntityFinder $objectFinder
    ) {
        parent::__construct('backlog', '0.1');

        $this->add(new SprintAddCommand($repositoryManager->getSprintRepository(), $objectCreator, $objectManager));
        $this->add(new SprintList($repositoryManager->getSprintRepository()));
        $this->add(new SprintUpdateCommand($objectFinder, $repositoryManager->getSprintRepository()));
        $this->add(new TeamAddCommand($repositoryManager->getTeamRepository(), $objectCreator));
        $this->add(new TeamList($repositoryManager->getTeamRepository()));
        $this->add(new JoinTeamCommand($objectFinder, $repositoryManager->getTeamMemberRepository(), $objectManager));
    }
}
