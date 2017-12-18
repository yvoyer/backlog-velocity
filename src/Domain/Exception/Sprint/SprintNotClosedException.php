<?php declare(strict_types=1);
/**
 * This file is part of the backlog-velocity project.
 *
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Domain\Exception\Sprint;

/**
 * @author  Yannick Voyer (http://github.com/yvoyer)
 */
class SprintNotClosedException extends \Exception implements SprintException
{
    /**
     * @param string $operation
     *
     * @return SprintNotClosedException
     */
    public static function cannotPerformOperationWhenNotEnded($operation)
    {
        return new self("Cannot {$operation} when the sprint is not closed.");
    }
}
