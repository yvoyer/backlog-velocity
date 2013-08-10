<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Interface EntityInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface EntityInterface
{
    /**
     * @return IdentifierInterface
     */
    public function getIdentifier();

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray();
}
