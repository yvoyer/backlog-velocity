<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Exception\Sprint;

use Star\Component\Sprint\Exception\BacklogException;

/**
 * Class AlreadyCommitedSprintMemberException
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Exception\Sprint
 */
class AlreadyCommittedSprintMemberException extends \Exception implements BacklogException
{
}
