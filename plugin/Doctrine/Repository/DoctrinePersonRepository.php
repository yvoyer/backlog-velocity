<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Domain\Exception\EntityNotFoundException;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrinePersonRepository extends EntityRepository implements PersonRepository
{
    /**
     * @param PersonName $name
     *
     * @return Person
     * @throws EntityNotFoundException
     */
    public function personWithName(PersonName $name) :Person
    {
        $person = $this->findOneBy(['name' => $name->toString()]);
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
        $this->_em->persist($person);
        $this->_em->flush();
    }

    /**
     * @return Person[]
     */
    public function allRegistered() :array
    {
        return $this->findAll();
    }

    /**
     * @param PersonName $name
     *
     * @return bool
     */
    public function personWithNameExists(PersonName $name) :bool
    {
        return (bool) $this->findOneBy(['name' => $name->toString()]);
    }

    /**
     * @param PersonId $personId
     *
     * @return bool
     */
    public function personWithIdExists(PersonId $personId): bool
    {
        return (bool) $this->findOneBy(['id' => $personId->toString()]);
    }
}
