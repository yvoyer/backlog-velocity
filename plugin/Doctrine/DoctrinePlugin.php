<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Star\BacklogVelocity\Application\Cli\BacklogApplication;
use Star\Component\Sprint\Domain\BacklogPlugin;
use Star\Component\Sprint\Domain\Entity\Factory\TeamFactory;
use Star\Component\Sprint\Domain\Entity\Factory\BacklogModelTeamFactory;
use Star\Component\Sprint\Domain\Repository\RepositoryManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrinePlugin implements BacklogPlugin
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $objectManager;

    /**
     * @param EntityManager $objectManager
     */
    public function __construct(EntityManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns the entity creator.
     *
     * @return TeamFactory
     */
    public function getTeamFactory()
    {
        return new BacklogModelTeamFactory();
    }

    /**
     * Returns the repository manager.
     *
     * @return RepositoryManager
     */
    public function getRepositoryManager()
    {
        return new DoctrineObjectManagerAdapter($this->objectManager);
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
        $application->addHelper('db', new ConnectionHelper($this->objectManager->getConnection()));
        $application->addHelper('em', new EntityManagerHelper($this->objectManager));
        $application->addCommands(array(
            // todo Remove to wrap in custom command backlog:upgrade
            new UpdateCommand(),
            // todo Remove to wrap in custom command backlog:uninstall
            new DropCommand(),
        ));

        self::install($application);
    }

    /**
     * @param array $configuration
     * @param string $environment
     * @param string $rootPath
     *
     * @return DoctrinePlugin
     * @throws \Doctrine\ORM\ORMException
     */
    public static function bootstrap(array $configuration, $environment, $rootPath)
    {
        $isDevMode = false;
        if ($environment === 'dev') {
            $isDevMode = true;
        }
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $path   = $rootPath . '/plugin/Doctrine/Resources/config/doctrine';
        $config = Setup::createXMLMetadataConfiguration(array($path), $isDevMode);

        $manager = EntityManager::create($configuration['database'], $config);

        return new self($manager);
    }

    /**
     * @param Application $application
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    private static function install(Application $application)
    {
        try {
            $command = new CreateCommand();
            $command->setHelperSet($application->getHelperSet());
            $command->run(new ArrayInput(array()), new NullOutput());
        } catch (\Exception $ex) {
            // Exception thrown when already installed
        }
    }
}