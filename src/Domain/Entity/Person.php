<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Entity;

use Star\Component\Sprint\Domain\Model\Identity\PersonId;
use Star\Component\Sprint\Domain\Model\PersonName;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface Person
{
    /**
     * @return PersonId
     */
    public function getId();

    /**
     * @return PersonName
     */
    public function getName();
}
