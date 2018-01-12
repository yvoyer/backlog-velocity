<?php declare(strict_types=1);

namespace Star\BacklogVelocity\Agile\Application\Calculator;

use Star\BacklogVelocity\Agile\Domain\Model\EstimatedFocusCalculator;
use Star\BacklogVelocity\Agile\Domain\Model\FocusFactor;
use Star\BacklogVelocity\Agile\Domain\Model\TeamId;

final class AlwaysReturnsFocus implements EstimatedFocusCalculator
{
    /**
     * @var int
     */
    private $focus;

    /**
     * @param int $focus
     */
    public function __construct(int $focus)
    {
        $this->focus = $focus;
    }

    /**
     * Estimated focus is used on Sprint creation.
     *
     * @param TeamId $teamId
     *
     * @return FocusFactor
     */
    public function calculateEstimatedFocus(TeamId $teamId): FocusFactor
    {
        return FocusFactor::fromInt($this->focus);
    }
}
