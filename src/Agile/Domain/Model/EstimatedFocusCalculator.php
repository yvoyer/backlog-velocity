<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Domain\Model;

interface EstimatedFocusCalculator
{
    /**
     * Estimated focus is used on Sprint creation.
     *
     * @param TeamId $teamId
     *
     * @return FocusFactor
     */
    public function calculateEstimatedFocus(TeamId $teamId): FocusFactor;
}
