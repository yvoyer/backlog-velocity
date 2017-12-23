<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model\Exception;

final class SprintLogicException extends \LogicException implements SprintException
{
    /**
     * @param string $state
     *
     * @return SprintLogicException
     */
    public static function cannotCommitSprintInState($state)
    {
        return new self("Cannot commit sprint when sprint is in state '{$state}'.");
    }
}
