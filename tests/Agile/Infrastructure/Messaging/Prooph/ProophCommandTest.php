<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Messaging\Prooph;

use PHPUnit\Framework\TestCase;

final class ProophCommandTest extends TestCase
{
    public function test_it_should_return_the_message_name(): void
    {
        $this->assertSame('backlog.do_something', (new DoSomething())->messageName());
    }
}

final class DoSomething extends ProophCommand {}
