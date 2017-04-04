<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Plugin\Null\Entity;

use Star\Component\Sprint\Model\Identity\PersonId;
use Star\Component\Sprint\Entity\Person;
use Star\Component\Sprint\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class NullPerson implements Person
{
    /**
     * @return PersonId
     */
    public function getId()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return PersonName
     */
    public function getName()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
