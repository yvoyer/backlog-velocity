<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * Class IdentifierInterface
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 */
interface IdentifierInterface
{
    /**
     * Returns the key.
     *
     * @return mixed
     */
    public function getKey();
}
