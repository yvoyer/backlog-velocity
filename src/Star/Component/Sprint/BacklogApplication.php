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
use Star\Component\Sprint\Command\ObjectCreatorCommand;
use Star\Component\Sprint\Command\Sprinter\AddCommand as SprinterAddCommand;
use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Factory\DefaultObjectFactory;
use Star\Component\Sprint\Entity\Factory\EntityCreatorInterface;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\ObjectManager;
use Star\Component\Sprint\Entity\Query\DoctrineObjectFinder;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamMemberRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Mapping\SprintData;
use Star\Component\Sprint\Mapping\SprinterData;
use Star\Component\Sprint\Mapping\TeamData;
use Star\Component\Sprint\Mapping\TeamMemberData;
use Star\Component\Sprint\Repository\DoctrineBridgeRepository;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\TableHelper;

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
     * @param mixed         $conn         An array with the connection parameters or an existing Connection instance.
     * @param Configuration $config       The Configuration instance to use.
     * @param DialogHelper  $dialogHelper
     */
    public function __construct($conn, Configuration $config, DialogHelper $dialogHelper)
    {
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

        $objectFactory = new InteractiveObjectFactory($dialogHelper);

        $objectManager = new ObjectManager(
            $objectFactory,
            new DoctrineObjectFinder($this->entityManager)
        );

        $sprintRepository = new SprintRepository(
            new DoctrineBridgeRepository(SprintData::LONG_NAME, $this->entityManager)
        );
        $sprinterRepository = new SprinterRepository(
            new DoctrineBridgeRepository(SprinterData::LONG_NAME, $this->entityManager)
        );
        $teamRepository = new TeamRepository(
            new DoctrineBridgeRepository(TeamData::LONG_NAME, $this->entityManager)
        );
        $teamMemberRepository = new TeamMemberRepository(
            new DoctrineBridgeRepository(TeamMemberData::LONG_NAME, $this->entityManager)
        );

        $this->add(
            new ObjectCreatorCommand(
                'backlog:team:add',
                EntityCreatorInterface::TYPE_TEAM,
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
            new ObjectCreatorCommand(
                'backlog:sprint:add',
                EntityCreatorInterface::TYPE_SPRINT,
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
