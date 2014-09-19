<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Entity\Id\PersonId;

/**
 * Class Person
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface Person
{
    const INTERFACE_NAME = __CLASS__;

    /**
     * @return PersonId
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}
 