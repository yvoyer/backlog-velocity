<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface PersonRepository
{
    /**
     * @param PersonName $name
     *
     * @return Person|null
     */
    public function personWithName(PersonName $name);

    /**
     * @param Person $person
     */
    public function savePerson(Person $person);

    /**
     * @return Person[]
     */
    public function allRegistered();
}
