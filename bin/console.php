#!/usr/bin/env php
<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace {
    $root = dirname(__DIR__);
    require_once $root . '/vendor/autoload.php';

    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Tools\Console\ConsoleRunner;
    use Doctrine\ORM\Tools\Setup;
    use Star\Component\Sprint\BacklogApplication;
    use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
    use Star\Component\Sprint\Entity\ObjectManager;
    use Star\Component\Sprint\Mapping\Repository\DefaultMapping;
    use Star\Plugin\Doctrine\DoctrineObjectManagerAdapter;
    use Star\Plugin\Doctrine\DoctrineObjectFinder;
    use Symfony\Component\Console\Helper\DialogHelper;

    $isDevMode = true;
    // $entityFolder = __DIR__ . '/Entity';
    // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
    $path   = $root . '/plugin/Star/Plugin/Doctrine/Resources/config/doctrine';
    $config = Setup::createXMLMetadataConfiguration(array($path), $isDevMode);

    $output = new \Symfony\Component\Console\Output\ConsoleOutput();

    $conn = array(
        'dbname'   => 'backlog_local',
        'user'     => 'username',
        'password' => 'password',
        'host'     => 'localhost',
        'driver'   => 'pdo_mysql',
    //    'driver' => 'pdo_sqlite',
    //    'path'   => $root . '/backlog.sqlite',
    );

    $entityManager = EntityManager::create($conn, $config);
    $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
    ));

    $mapping           = new DefaultMapping();
    $repositoryManager = new DoctrineObjectManagerAdapter($entityManager, $mapping);
    $objectFinder      = new DoctrineObjectFinder($repositoryManager);
    $dialogHelper      = new DialogHelper();
    $objectCreator     = new InteractiveObjectFactory($dialogHelper, $output);
    $objectManager     = new ObjectManager($objectCreator, $objectFinder);

    $console = new BacklogApplication($repositoryManager, $objectManager, $objectCreator, $objectFinder);
    $console->setHelperSet($helperSet);
    ConsoleRunner::addCommands($console);
    $console->run(null, $output);
}
