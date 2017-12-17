<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Repository;

use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullPersonRepository implements PersonRepository
{
    /**
     * @param PersonName $name
     *
     * @return Person
     * @throws EntityNotFoundException
     */
    public function personWithName(PersonName $name): Person
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param PersonName $name
     *
     * @return bool
     */
    public function personWithNameExists(PersonName $name): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param PersonId $personId
     *
     * @return bool
     */
    public function personWithIdExists(PersonId $personId): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
    public function allRegistered(): array
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
