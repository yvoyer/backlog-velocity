<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Infrastructure\Persistence\Collection;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;
use Star\BacklogVelocity\Agile\Domain\Model\Person;
use Star\BacklogVelocity\Agile\Domain\Model\PersonId;
use Star\BacklogVelocity\Agile\Domain\Model\PersonName;
use Star\BacklogVelocity\Agile\Domain\Model\PersonRepository;
use Star\Component\Collection\TypedCollection;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class PersonCollection implements PersonRepository, \Countable
{
    /**
     * @var Person[]|TypedCollection
     */
    private $elements;

    public function __construct(array $persons = array())
    {
        $this->elements = new TypedCollection(Person::class, $persons);
    }

    /**
     * @param PersonName $name
     *
     * @return Person
     * @throws EntityNotFoundException
     */
    public function personWithName(PersonName $name): Person
    {
        $person = $this->elements->filter(function (Person $p) use ($name) {
            // todo implement equalsTo(PersonName) : bool
            return $name->toString() === $p->getName()->toString();
        })->first();

        if (! $person) {
            throw EntityNotFoundException::objectWithAttribute(Person::class, 'name', $name->toString());
        }

        return $person;
    }

    public function savePerson(Person $person): void
    {
        $this->elements[] = $person;
    }

    /**
     * @return Person[]
     */
    public function allRegistered(): array
    {
        return $this->elements->getValues();
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function personWithNameExists(PersonName $name): bool
    {
        return $this->elements->exists(function ($key, Person $person) use ($name) {
            return $name->equals($person->getName());
        });
    }

    public function personWithIdExists(PersonId $personId): bool
    {
        return $this->elements->exists(function ($key, Person $person) use ($personId) {
            return $personId->matchIdentity($person->getId());
        });
    }
}
