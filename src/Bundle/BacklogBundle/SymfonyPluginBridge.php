<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Star\BacklogVelocity\Agile\BacklogPlugin;
use Star\BacklogVelocity\Agile\Domain\Model\BacklogModelTeamFactory;
use Star\BacklogVelocity\Agile\Domain\Model\PersonModel;
use Star\BacklogVelocity\Agile\Domain\Model\ProjectAggregate;
use Star\BacklogVelocity\Agile\Domain\Model\SprintModel;
use Star\BacklogVelocity\Agile\Domain\Model\TeamFactory;
use Star\BacklogVelocity\Agile\Domain\Model\TeamModel;
use Star\BacklogVelocity\Agile\Infrastructure\Persistence\Doctrine\DoctrinePlugin;
use Star\BacklogVelocity\Cli\BacklogApplication;

final class SymfonyPluginBridge implements BacklogPlugin
{
    /**
     * @var \AppKernel
     */
    private $kernel;

    /**
     * @param \AppKernel $kernel
     */
    public function __construct(\AppKernel $kernel)
    {
        $this->kernel = $kernel;
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
        $container = $this->kernel->getContainer();

        return new class($container) {
            /**
             * @var EntityManagerInterface
             */
            private $em;

            public function __construct(ContainerInterface $container)
            {
                $this->em = $container->get('doctrine.orm.entity_manager');
            }

            public function getPersonRepository()
            {
                return $this->em->getRepository(PersonModel::class);
            }

            public function getTeamRepository()
            {
                return $this->em->getRepository(TeamModel::class);
            }

            public function getProjectRepository()
            {
                return $this->em->getRepository(ProjectAggregate::class);
            }

            public function getSprintRepository()
            {
                return $this->em->getRepository(SprintModel::class);
            }
        };
    }

    /**
     * Hook to inject custom application changes.
     *
     * @param BacklogApplication $application
     */
    public function build(BacklogApplication $application)
    {
        $this->kernel->boot();
        $doctrine = new DoctrinePlugin(
            $this->kernel->getContainer()->get('doctrine.orm.entity_manager')
        );
        $doctrine->build($application);
    }
}
