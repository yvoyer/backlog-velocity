<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Repository;

use Star\Component\Sprint\Entity\Person;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface PersonRepository
{
    /**
     * Find the object based on name.
     *
     * @param string $name
     *
     * @return Person|null
     */
    public function findOneByName($name);

    /**
     * @param Person $person
     */
    public function savePerson(Person $person);

    /**
     * @return Person[]
     */
    public function allRegistered();
}
