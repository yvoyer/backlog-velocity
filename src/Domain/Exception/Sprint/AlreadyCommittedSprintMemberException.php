<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Exception\Sprint;

use Star\Component\Sprint\Domain\Exception\BacklogException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class AlreadyCommittedSprintMemberException extends \Exception implements BacklogException
{
}
