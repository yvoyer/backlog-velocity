<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Repository;

use Star\Component\Sprint\Entity\EntityInterface;

/**
 * Class WrappedRepository
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Repository
 */
class WrappedRepository implements Repository
{
    /**
     * @var Repository
     */
    private $wrappedRepository;

    /**
     * @param Repository $wrappedRepository
     */
    public function __construct(Repository $wrappedRepository)
    {
        $this->wrappedRepository = $wrappedRepository;
    }

    /**
     * Returns all the object from one repository.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->wrappedRepository->findAll();
    }

    /**
     * Returns the object linked with the $id.
     *
     * @param mixed $id
     *
     * @return object
     */
    public function find($id)
    {
        return $this->wrappedRepository->find($id);
    }

    /**
     * Add the $object linked to the $id.
     *
     * @param EntityInterface $object
     */
    public function add(EntityInterface $object)
    {
        $this->wrappedRepository->add($object);
    }

    /**
     * Save the $object in the repository.
     */
    public function save()
    {
        $this->wrappedRepository->save();
    }
}
