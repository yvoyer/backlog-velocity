<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Model\Identity\ProjectId;

/**
 * Class Project
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 *
 * Contract for project classes.
 */
interface Project
{
    /**
     * @return ProjectId
     */
    public function getIdentity();
}
