<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Mapping\Entity;

/**
 * Class Sprinter
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Entity
 * @deprecated todo use of Person and SprintMember
 */
interface Sprinter extends Entity
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();
}
