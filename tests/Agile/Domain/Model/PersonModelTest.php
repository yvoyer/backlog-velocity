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

    public function setUp()
    {
        $this->person = PersonModel::fromString('id', 'name');
    }

    public function test_should_return_id()
    {
        $this->assertSame('id', $this->person->getId()->toString());
    }

    public function test_should_have_a_name()
    {
        $this->assertSame('name', $this->person->getName()->toString());
    }

    /**
     * @expectedException        \Assert\InvalidArgumentException
     * @expectedExceptionMessage Person name "" is empty, but non empty value was expected.
     */
    public function test_should_have_a_valid_name()
    {
        new PersonModel(PersonId::fromString('id'), new PersonName(''));
    }
}
