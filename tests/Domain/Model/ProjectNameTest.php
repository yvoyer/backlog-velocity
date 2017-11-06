<?php declare(strict_types=1);

namespace Star\Component\Sprint\Domain\Model;

use PHPUnit\Framework\TestCase;

final class ProjectNameTest extends TestCase
{
    public function test_it_should_check_for_equality()
    {
        $origin = new ProjectName('original');
        $this->assertTrue($origin->equalsTo($origin));
        $this->assertTrue($origin->equalsTo(new ProjectName('original')));
        $this->assertFalse($origin->equalsTo(new ProjectName('not-same')));
    }

    public function test_it_should_be_an_attribute()
    {
        $name = new ProjectName('Some name');
        $this->assertInstanceOf(Attribute::class, $name);
        $this->assertSame('project name', $name->attributeName());
        $this->assertSame('Some name', $name->attributeValue());
    }
}
