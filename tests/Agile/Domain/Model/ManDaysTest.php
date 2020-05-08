<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;

final class ManDaysTest extends TestCase
{
    public function test_it_should_accept_string_value()
    {
        $this->assertSame(12, ManDays::fromString('12')->toInt());
    }

    public function test_it_should_add_man_days()
    {
        $original = ManDays::fromInt(3);
        $this->assertSame(3, $original->toInt());
        $this->assertSame(5, $original->addManDays(ManDays::fromInt(2))->toInt());
        $this->assertSame(3, $original->toInt());
    }

    public function test_it_should_accept_zero_values()
    {
        $this->assertSame(0, ManDays::fromInt(0)->toInt());
    }
}
