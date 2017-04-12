<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Exception\EntityNotFoundException;
use Star\Component\Sprint\Model\PersonName;

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
    public function personWithName(PersonName $name)
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

    /**
     * @param Person $person
     */
    public function savePerson(Person $person)
    {
        $this->elements[] = $person;
    }

    /**
     * @return Person[]
     */
    public function allRegistered()
    {
        return $this->elements->getValues();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param PersonName $name
     *
     * @return bool
     */
    public function personWithNameExists(PersonName $name)
    {
        return $this->elements->exists(function ($key, Person $person) use ($name) {
            return $name->equals($person->getName());
        });
    }
}
