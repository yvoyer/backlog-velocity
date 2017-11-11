<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Exception;

use Star\Component\Identity\Exception\EntityNotFoundException as BaseException;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class EntityNotFoundException extends BaseException implements BacklogException
{
}
