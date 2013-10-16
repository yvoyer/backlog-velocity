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
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\Command\Sprint\AddCommand as SprintAddCommand;
use Star\Component\Sprint\Command\Sprinter\AddCommand as SprinterAddCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
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

        $objectFactory = new InteractiveObjectFactory($dialogHelper, $output);

        $mapping = new DefaultMapping();

        $adapter = new DoctrineObjectManagerAdapter($this->entityManager, $mapping);
        $objectFinder = new DoctrineObjectFinder($adapter);

        $this->add(
            new TeamAddCommand(
                $adapter->getTeamRepository(),
                $objectFactory
            )
        );
        $this->add(new ListCommand($adapter->getTeamRepository()));
        $this->add(
            new SprinterAddCommand(
                $adapter->getSprinterRepository(),
                $objectFactory
            )
        );
        $this->add(new JoinTeamCommand($objectFinder, $adapter->getTeamMemberRepository()));
        $this->add(
            new SprintAddCommand(
                $adapter->getSprintRepository(),
                $objectFactory
            )
        );
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
