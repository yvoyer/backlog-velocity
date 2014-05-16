<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Star\Component\Sprint\BacklogApplication;
use Star\Component\Sprint\Entity\Factory\EntityCreator;
use Star\Component\Sprint\Entity\Factory\InteractiveObjectFactory;
use Star\Component\Sprint\Entity\Query\EntityFinder;
use Star\Component\Sprint\Plugin\BacklogPlugin;
use Star\Component\Sprint\Repository\RepositoryManager;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DoctrinePlugin
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Plugin\Doctrine
 */
class DoctrinePlugin implements BacklogPlugin
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $objectManager;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Returns the entity creator.
     *
     * @return EntityCreator
     */
    public function getEntityCreator()
    {
        return new InteractiveObjectFactory(new DialogHelper(), $this->output);
    }

    /**
     * Returns the entity finder.
     *
     * @return EntityFinder
     */
    public function getEntityFinder()
    {
        return new DoctrineObjectFinder($this->getRepositoryManager());
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
        $isDevMode = false;
        $configuration = $application->getConfiguration();
        if ($application->getEnvironment() === 'dev') {
            $isDevMode = true;
        }
        // $entityFolder = __DIR__ . '/Entity';
        // $config = Setup::createAnnotationMetadataConfiguration(array($entityFolder), $isDevMode);
        $path   = $application->getRootPath() . '/plugin/Star/Plugin/Doctrine/Resources/config/doctrine';
        $config = Setup::createXMLMetadataConfiguration(array($path), $isDevMode);

        $this->objectManager = EntityManager::create($configuration['database'], $config);
        $application->addHelper('db', new ConnectionHelper($this->objectManager->getConnection()));
        $application->addHelper('em', new EntityManagerHelper($this->objectManager));

        $application->addCommands(array(
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\InfoCommand()
        ));
    }
}
 