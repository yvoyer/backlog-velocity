<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity\Null;

use Star\Component\Sprint\Entity\SprintMember;

/**
 * Class NullSprintMember
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity\Null
 */
class NullSprintMember implements SprintMember
{
    /**
     * Returns the unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return null;
    }

    /**
     * Returns the array representation of the object.
     *
     * @return array
     */
    public function toArray()
    {
        return array();
    }
}
