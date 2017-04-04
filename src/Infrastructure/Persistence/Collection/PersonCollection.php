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
use Star\Component\Sprint\Model\Identity\PersonId;
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
     * @return Person|null
     */
    public function personWithName(PersonName $name)
    {
        return $this->elements->filter(function (Person $p) use ($name) {
            // todo implement equalsTo(PersonName) : bool
            return $name->toString() === $p->getName()->toString();
        })->first();
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
}
