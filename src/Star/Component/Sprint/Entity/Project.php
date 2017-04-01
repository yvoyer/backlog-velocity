<?php
/**
 * This file is part of the backlog-velocity.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Entity;

use Star\Component\Sprint\Model\Identity\ProjectId;
use Star\Component\Sprint\Model\Identity\SprintId;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * Contract for project classes.
 */
interface Project
{
    /**
     * @return ProjectId
     */
    public function getIdentity();

    /**
     * @param SprintId $sprintId
     * @param \DateTimeInterface $createdAt
     *
     * @return Sprint
     */
    public function createSprint(SprintId $sprintId, \DateTimeInterface $createdAt);
}
