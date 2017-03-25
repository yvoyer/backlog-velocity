<?php

namespace Star\Component\Sprint\Exception\Sprint;

final class SprintNotStartedException extends \RuntimeException implements SprintException
{
    /**
     * @param string $operation
     *
     * @return SprintNotStartedException
     */
    public static function cannotPerformOperationWhenNotStarted($operation)
    {
        return new self("Cannot {$operation} when the sprint was never started.");
    }
}
