<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity\Repository;

use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\PersonName;

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
    public function personWithName(PersonName $name);

    /**
     * @param PersonName $name
     *
     * @return bool
     */
    public function personWithNameExists(PersonName $name);

    /**
     * @param Person $person
     */
    public function savePerson(Person $person);

    /**
     * @return Person[]
     */
    public function allRegistered();
}
