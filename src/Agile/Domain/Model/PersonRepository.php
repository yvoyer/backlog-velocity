<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\BacklogVelocity\Agile\Domain\Model;

use Star\BacklogVelocity\Agile\Domain\Model\Exception\EntityNotFoundException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface PersonRepository
{
    /**
     * @param PersonName $name
     *
     * @return Person
     * @throws EntityNotFoundException
     */
    public function personWithName(PersonName $name) :Person;

    /**
     * @param PersonName $name
     *
     * @return bool
     */
    public function personWithNameExists(PersonName $name) : bool;

    /**
     * @param PersonId $personId
     *
     * @return bool
     */
    public function personWithIdExists(PersonId $personId) : bool;

    /**
     * @param Person $person
     */
    public function savePerson(Person $person);

    /**
     * @return Person[]
     */
    public function allRegistered() :array;
}
