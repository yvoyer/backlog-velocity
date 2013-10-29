<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Star\Component\Sprint\Command\Sprint\AddCommand as SprintAddCommand;
use Star\Component\Sprint\Command\Sprint\UpdateCommand as SprintUpdateCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Team\JoinCommand as JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand as TeamList;
use Star\Component\Sprint\Command\Sprint\ListCommand as SprintList;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Mapping\Repository\DefaultMapping;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @param mixed           $conn         An array with the connection parameters or an existing Connection instance.
     * @param Configuration   $config       The Configuration instance to use.
     * @param DialogHelper    $dialogHelper
     * @param OutputInterface $output
     */
    public function __construct(
        $conn,
        Configuration $config,
        DialogHelper $dialogHelper,
        OutputInterface $output
    ) {
        parent::__construct('backlog', '0.1');

        // obtaining the entity manager
        $this->entityManager = EntityManager::create($conn, $config);
        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db'        => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($this->entityManager->getConnection()),
            'em'        => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($this->entityManager),
            'formatter' => new FormatterHelper(),
            'dialog'    => $dialogHelper,
            'progress'  => new ProgressHelper(),
            'table'     => new TableHelper(),
        ));
        $this->setHelperSet($helperSet);
        ConsoleRunner::addCommands($this);

        $mapping           = new DefaultMapping();
        $repositoryManager = new DoctrineObjectManagerAdapter($this->entityManager, $mapping);
        $objectFinder      = new DoctrineObjectFinder($repositoryManager);
        $objectCreator     = new InteractiveObjectFactory($dialogHelper, $output);
        $objectManager     = new ObjectManager($objectCreator, $objectFinder);

        $this->add(new SprintAddCommand($repositoryManager->getSprintRepository(), $objectCreator, $objectManager));
        $this->add(new SprintList($repositoryManager->getSprintRepository()));
        $this->add(new SprintUpdateCommand($objectFinder, $repositoryManager->getSprintRepository()));
        $this->add(new TeamAddCommand($repositoryManager->getTeamRepository(), $objectCreator));
        $this->add(new TeamList($repositoryManager->getTeamRepository()));
        $this->add(new JoinTeamCommand($objectFinder, $repositoryManager->getTeamMemberRepository(), $objectManager));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
