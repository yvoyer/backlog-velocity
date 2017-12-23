<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Messaging\Prooph;

use Doctrine\Common\Inflector\Inflector;
use Prooph\Common\Messaging\Command;

abstract class ProophCommand extends Command
{
    /**
     * Return message payload as array
     *
     * The payload should only contain scalar types and sub arrays.
     * The payload is normally passed to json_encode to persist the message or
     * push it into a message queue.
     *
     * @return array
     */
    public function payload()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     *
     * @param array $payload
     * @return void
     */
    protected function setPayload(array $payload)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return string Name of the message
     */
    public function messageName()
    {
        $class = static::class;
        $class = substr($class, strrpos($class, '\\') + 1);

        return 'backlog.' . Inflector::tableize($class);
    }
}
