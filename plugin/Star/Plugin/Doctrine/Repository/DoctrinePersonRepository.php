<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Model\Identity\PersonId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrinePersonRepository implements PersonRepository
{
    /**
     * @param PersonId $id
     *
     * @return Person|null
     */
    public function findOneById(PersonId $id)
    {
        // todo add tests
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @param Person $person
     */
    public function savePerson(Person $person)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return Person[]
     */
    public function allRegistered()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
