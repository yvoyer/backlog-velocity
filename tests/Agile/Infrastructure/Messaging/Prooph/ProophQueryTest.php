<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Infrastructure\Messaging\Prooph;

use PHPUnit\Framework\TestCase;

final class ProophQueryTest extends TestCase
{
    public function test_it_should_return_the_query_custom_name()
    {
        $query = new FetchSomeEntity();
        $this->assertSame('backlog.fetch_some_entity', $query->messageName());
    }
}

final class FetchSomeEntity extends ProophQuery {}
