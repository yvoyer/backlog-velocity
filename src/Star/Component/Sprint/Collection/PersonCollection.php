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

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class PersonCollection implements PersonRepository
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
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Person|null
     */
    public function findOneByName($name)
    {
        return $this->elements->filter(function (Person $p) use ($name) {
            return $p->getId()->toString() === $name;
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
}
