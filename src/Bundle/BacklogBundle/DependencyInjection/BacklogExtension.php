<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class BacklogExtension extends Extension {
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('cli_commands.yml');
        $loader->load('controllers.yml');
        $loader->load('cqrs_commands.yml');
        $loader->load('cqrs_queries.yml');
        $loader->load('repositories.yml');
        $loader->load('forms.yml');
        $loader->load('services.yml');
    }
}
