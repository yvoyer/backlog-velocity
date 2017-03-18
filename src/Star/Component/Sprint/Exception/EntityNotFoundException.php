<?php
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Exception;

use Star\Component\Identity\Exception\EntityNotFoundException as BaseException;

/**
 * Class EntityNotFoundException
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Exception
 */
class EntityNotFoundException extends BaseException implements BacklogException
{
}
