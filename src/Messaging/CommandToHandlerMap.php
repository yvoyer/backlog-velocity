<?php

namespace Star\Component\Messaging;

final class CommandToHandlerMap implements CommandBus
{
    /**
     * @var CommandHandler[]
     */
    private $handlers = [];

    /**
     * @param string $commandClass
     * @param CommandHandler $handler
     * @internal
     */
    public function registerHandler($commandClass, CommandHandler $handler) {
        $this->handlers[$commandClass] = $handler;
    }

    /**
     * @param DomainCommand $command
     */
    public function handle(DomainCommand $command)
    {
        $className = get_class($command);
        if (! array_key_exists($className, $this->handlers)) {
            throw new \RuntimeException("No handler is registered to handle the command '{$className}'.");
        }

        $this->handlers[$className]->execute($command);
    }
}
