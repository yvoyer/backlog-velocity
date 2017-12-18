<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use PHPUnit\Framework\TestCase;

final class ManDaysTest extends TestCase
{
    public function test_it_should_accept_string_value()
    {
        $this->assertSame(12, ManDays::fromString('12')->toInt());
    }

    /**
     * @ticket #57
     *
     * @expectedException        \TypeError
     * @expectedExceptionMessage Argument 1 passed to Star\Component\Sprint\Domain\Model\ManDays::fromInt() must be of the type integer
     */
    public function test_should_throw_exception_when_invalid_man_days()
    {
        ManDays::fromInt('j');
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
