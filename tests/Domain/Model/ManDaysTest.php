<?php

namespace Star\Component\Sprint\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Sprint\Model\ManDays;

final class ManDaysTest extends TestCase
{
    public function test_it_should_accept_string_value()
    {
        $this->assertSame(12, ManDays::fromInt('12')->toInt());
    }

    /**
     * @ticket #57
     * @dataProvider provideInvalidManDays
     *
     * @param $manDays
     *
     * @expectedException        \Star\Component\Sprint\Exception\InvalidAssertionException
     * @expectedExceptionMessage The man days must be a numeric greater than zero,
     */
    public function test_should_throw_exception_when_invalid_man_days($manDays)
    {
        ManDays::fromInt($manDays);
    }

    public function provideInvalidManDays()
    {
        return array(
            'Man days cannot be negative' => array(-1),
            'Man days cannot be array' => array(array()),
            'Man days cannot be bool false' => array(false),
            'Man days cannot be bool true' => array(true),
            'Man days cannot be string' => array(''),
            'Man days cannot be float' => array(213.321),
        );
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
