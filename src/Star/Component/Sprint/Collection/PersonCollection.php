<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Collection;

use Star\Component\Collection\TypedCollection;
use Star\Component\Sprint\Entity\Person;

/**
 * Class PersonCollection
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Collection
 */
class PersonCollection extends TypedCollection
{
    public function __construct(array $persons = array())
    {
        parent::__construct('Star\Component\Sprint\Entity\Person', $persons);
    }

    protected function create(array $elements = array())
    {
        return new self($elements);
    }

    /**
     * @param Person $person
     *
     * @deprecated todo use addPerson
     */
    public function add($person)
    {
        $this->addPerson($person);
    }

    /**
     * @param Person $person
     */
    public function addPerson(Person $person)
    {
        $this[] = $person;
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
        foreach ($this as $person) {
            if ($person->getName() === $name) {
                return $person;
            }
        }

        return null;
    }
}
 