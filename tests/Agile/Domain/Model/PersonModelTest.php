<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use PHPUnit\Framework\TestCase;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
final class PersonModelTest extends TestCase
{
    /**
     * @var PersonModel
     */
    private $person;

	protected function setUp(): void
    {
        $this->person = PersonModel::fromString('id', 'name');
    }

    public function test_should_return_id(): void
    {
        $this->assertSame('id', $this->person->getId()->toString());
    }

    public function test_should_have_a_name(): void
    {
        $this->assertSame('name', $this->person->getName()->toString());
    }

    public function test_should_have_a_valid_name(): void
    {
    	$this->expectException(\InvalidArgumentException::class);
    	$this->expectExceptionMessage('Person name "" is empty, but non empty value was expected.');
        new PersonModel(PersonId::fromString('id'), new PersonName(''));
    }
}
