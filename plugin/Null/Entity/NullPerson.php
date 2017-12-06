<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Domain\Model\Identity\MemberId;
use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Entity\Person;
use Star\Component\Sprint\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullPerson implements Person
{
    /**
     * @var PersonId
     */
    private $id;

    public function __construct()
    {
        $this->id = PersonId::uuid();
    }

    /**
     * @return PersonId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PersonName
     */
    public function getName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return MemberId
     */
    public function memberId(): MemberId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
