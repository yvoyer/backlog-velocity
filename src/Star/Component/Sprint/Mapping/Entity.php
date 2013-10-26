<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

/**
 * Interface Entity
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 */
interface Entity
{
    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray();

    /**
     * Returns whether the entity is valid.
     *
     * @return bool
     */
    public function isValid();
}
