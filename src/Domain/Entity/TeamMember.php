<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
interface TeamMember
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function matchPerson($name);
}
