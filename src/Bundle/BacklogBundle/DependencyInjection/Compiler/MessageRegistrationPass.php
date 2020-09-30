<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Bundle\BacklogBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MessageRegistrationPass implements CompilerPassInterface {
	const COMMAND_HANDLER_TAG = 'backlog.command_handler';
	const QUERY_HANDLER_TAG = 'backlog.query_handler';

	public function process(ContainerBuilder $container) {
		$this->registerCommandHandlers($container);
		$this->registerQueryHandlers($container);
	}

	private function registerCommandHandlers(ContainerBuilder $container): void {
		$definitions = [];
		$handlers = $container->findTaggedServiceIds(self::COMMAND_HANDLER_TAG);
		$router = $container->getDefinition('prooph_service_bus.command_bus_router');
		foreach ($handlers as $serviceId => $tags) {
			foreach ($tags as $tag) {
				$handler = $container->getDefinition($serviceId);
				$command = \str_replace('Handler', '', $handler->getClass());
				$handler->addTag(
					'prooph_service_bus.backlog_command_bus.route_target',
					[
						'message' => $command,
					]
				);
				$definitions[] = $handler;
			}
		}
		$router->setArguments($definitions);
	}

	private function registerQueryHandlers(ContainerBuilder $container): void {
		$definitions = [];
		$handlers = $container->findTaggedServiceIds(self::QUERY_HANDLER_TAG);
		$router = $container->getDefinition('prooph_service_bus.query_bus_router');
		foreach ($handlers as $serviceId => $tags) {
			foreach ($tags as $tag) {
				$handler = $container->getDefinition($serviceId);
				$query = \str_replace('Handler', '', $handler->getClass());
				$handler->addTag(
					'prooph_service_bus.backlog_query_bus.route_target',
					[
						'message' => $query,
					]
				);
				$definitions[] = $handler;
			}
		}
		$router->setArguments($definitions);
	}
}
