<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Entity\Repository\PersonRepository;
use Star\Component\Sprint\Model\Identity\PersonId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class DoctrinePersonRepository extends EntityRepository implements PersonRepository
{
    /**
     * @param PersonId $id
     *
     * @return Person|null
     */
    public function findOneById(PersonId $id)
    {
        return $this->find($id->toString());
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
    public function allRegistered()
    {
        return $this->findAll();
    }
}
