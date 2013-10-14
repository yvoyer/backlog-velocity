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
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Repository\Doctrine\DoctrineObjectManagerAdapter;
use Star\Component\Sprint\Repository\Doctrine\DoctrineSprinterRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineSprintRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineTeamMemberRepository;
use Star\Component\Sprint\Repository\Doctrine\DoctrineTeamRepository;
use Star\Component\Sprint\Repository\Mapping;
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

        $mapping = new Mapping(
            TeamData::LONG_NAME,
            SprintData::LONG_NAME,
            SprinterData::LONG_NAME,
            TeamMemberData::LONG_NAME,
            SprinterData::LONG_NAME
        );

        $adapter = new DoctrineObjectManagerAdapter($this->entityManager, $mapping);

        $objectManager = new ObjectManager(
            $objectFactory,
            new DoctrineObjectFinder($adapter)
        );

        $sprintRepository     = new DoctrineSprintRepository($adapter);
        $sprinterRepository   = new DoctrineSprinterRepository($adapter);
        $teamRepository       = new DoctrineTeamRepository($adapter);
        $teamMemberRepository = new DoctrineTeamMemberRepository($adapter);

        $this->add(
            new TeamAddCommand(
                $teamRepository,
                $objectFactory
            )
        );
        $this->add(new ListCommand($teamRepository));
        $this->add(
            new SprinterAddCommand(
                $sprinterRepository,
                $objectFactory
            )
        );
        $this->add(new JoinTeamCommand($objectManager, $teamMemberRepository));
        $this->add(
            new SprintAddCommand(
                $sprintRepository,
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
