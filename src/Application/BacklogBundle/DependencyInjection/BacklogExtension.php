<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\DependencyInjection;

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
        $loader->load('commands.yml');
        $loader->load('controllers.yml');
        $loader->load('repositories.yml');
        $loader->load('projections.yml');
        $loader->load('forms.yml');
    }
}
