<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\Command\Team\AddCommand;
use Star\Component\Sprint\Command\Team\ListCommand;
use Star\Component\Sprint\Entity\Repository\TeamRepository;
use Star\Component\Sprint\Entity\Team;
use Star\Component\Sprint\Repository\DoctrineBridgeRepository;
use Star\Component\Sprint\Repository\YamlFileRepository;
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
     * @param string $dataFolder The base data folder path.
     */
    public function __construct($dataFolder)
    {
        parent::__construct('backlog', '0.1');

        $isDevMode = true;
        $configFolder = $dataFolder . '/../config/doctrine';
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $config = Setup::createXMLMetadataConfiguration(array($configFolder), $isDevMode);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'path'   => $dataFolder . '/../backlog.sqlite',
        );

        // obtaining the entity manager
        $entityManager = EntityManager::create($conn, $config);

        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db'        => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
            'em'        => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
            'formatter' => new FormatterHelper(),
            'dialog'    => new DialogHelper(),
            'progress'  => new ProgressHelper(),
            'table'     => new TableHelper(),
        ));
        $this->setHelperSet($helperSet);

        ConsoleRunner::addCommands($this);

        $teamRepository = new TeamRepository(new DoctrineBridgeRepository(Team::LONG_NAME, $entityManager));

        $this->add(new AddCommand($teamRepository));
        $this->add(new ListCommand($teamRepository));
    }
}
