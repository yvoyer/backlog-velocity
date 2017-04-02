<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Exception\Sprint;

use Star\Component\Sprint\Exception\BacklogException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class AlreadyCommittedSprintMemberException extends \Exception implements BacklogException
{
}
