<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Messaging\Prooph;

use Doctrine\Common\Inflector\Inflector;
use Prooph\Common\Messaging\Query;

abstract class ProophQuery extends Query
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
    public function payload(): array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     *
     * @param array $payload
     * @return void
     */
    protected function setPayload(array $payload): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function messageName(): string
    {
        $class = static::class;
        $class = substr($class, strrpos($class, '\\') + 1);

        return 'backlog.' . Inflector::tableize($class);
    }
}
