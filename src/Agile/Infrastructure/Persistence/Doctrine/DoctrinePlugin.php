<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\Assert;
use Star\BacklogVelocity\Agile\BacklogPlugin;
use Star\BacklogVelocity\Agile\RepositoryManager;
use Star\BacklogVelocity\Cli\BacklogApplication;
use Star\BacklogVelocity\Cli\Commands\Adapter\Doctrine\UpdateApplicationCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class DoctrinePlugin implements BacklogPlugin
{
    /**
     * @var EntityManagerInterface
     */
    private $objectManager;

    /**
     * @param EntityManagerInterface $objectManager
     */
    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
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
        $application->add(new UpdateApplicationCommand());
    }

    /**
     * @param array $configuration
     * @param string $environment
     *
     * @return DoctrinePlugin
     * @throws \Doctrine\ORM\ORMException
     * @deprecated todo Remove at some point
     */
    public static function bootstrap(array $configuration, $environment)
    {
        $path = __DIR__ . '/Resources/mappings';
        $config = Setup::createXMLMetadataConfiguration(array($path), $environment !== 'prod');

        $manager = EntityManager::create($configuration['database'], $config);
        $setup = new UpdateCommand();
        $setup->setHelperSet(
            new HelperSet(
                [
                    'em' => new EntityManagerHelper($manager),
                ]
            )
        );
        Assert::assertSame(
            0,
            $setup->run(new ArrayInput(['--force' => true]), new NullOutput()),
            'An error occured during db creation'
        );

        return new self($manager);
    }
}
