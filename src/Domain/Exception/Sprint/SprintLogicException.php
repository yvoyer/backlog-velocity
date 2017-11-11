<?php

namespace Star\Component\Sprint\Domain\Exception\Sprint;

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
