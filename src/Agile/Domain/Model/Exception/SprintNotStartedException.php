<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

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
