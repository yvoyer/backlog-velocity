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
use Star\Component\Sprint\Command\Sprinter\JoinTeamCommand;
use Star\Component\Sprint\Command\Team\AddCommand as TeamAddCommand;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Repository\SprinterRepository;
use Star\Component\Sprint\Entity\Repository\SprintRepository;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Sprint;
use Star\Component\Sprint\Entity\Sprinter;
use Star\Component\Sprint\Entity\Team;
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
     * @param mixed $conn An array with the connection parameters or an existing
     *      Connection instance.
     * @param Configuration $config The Configuration instance to use.
     */
    public function __construct($conn, Configuration $config)
    {
        parent::__construct('backlog', '0.1');

        // obtaining the entity manager
        $this->entityManager = EntityManager::create($conn, $config);

        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db'        => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($this->entityManager->getConnection()),
            'em'        => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($this->entityManager),
            'formatter' => new FormatterHelper(),
            'dialog'    => new DialogHelper(),
            'progress'  => new ProgressHelper(),
            'table'     => new TableHelper(),
        ));
        $this->setHelperSet($helperSet);

        ConsoleRunner::addCommands($this);

        $sprintRepository = new SprintRepository(
            new DoctrineBridgeRepository(Sprint::LONG_NAME, $this->entityManager)
        );
        $sprinterRepository = new SprinterRepository(
            new DoctrineBridgeRepository(Sprinter::LONG_NAME, $this->entityManager)
        );
        $teamRepository = new TeamRepository(
            new DoctrineBridgeRepository(Team::LONG_NAME, $this->entityManager)
        );
        $objectFactory  = new InteractiveObjectFactory();

        $this->add(new TeamAddCommand($teamRepository, $objectFactory));
        $this->add(new ListCommand($teamRepository));
        $this->add(new SprinterAddCommand($sprinterRepository, $objectFactory));
        $this->add(new JoinTeamCommand($sprinterRepository, $teamRepository));
        $this->add(new SprintAddCommand($sprintRepository, $objectFactory));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
