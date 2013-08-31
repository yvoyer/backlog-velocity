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
use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
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

        $teamRepository = new TeamRepository(new DoctrineBridgeRepository(Team::LONG_NAME, $this->entityManager));

        $this->add(new AddCommand($teamRepository, new InteractiveObjectFactory()));
        $this->add(new ListCommand($teamRepository));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
